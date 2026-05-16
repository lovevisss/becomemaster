<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Project;
use DOMDocument;
use DOMElement;
use DOMXPath;
use RuntimeException;
use ZipArchive;

class BigPayFormExporter
{
    public function export(Project $project, Contract $contract): array
    {
        $templatePath = resource_path('templates/big-pay-form.docx');

        if (!is_file($templatePath)) {
            throw new RuntimeException('Big pay form template not found.');
        }

        $outputPath = tempnam(storage_path('app'), 'big-pay-form-');
        copy($templatePath, $outputPath);

        $zip = new ZipArchive();
        if ($zip->open($outputPath) !== true) {
            throw new RuntimeException('Unable to open generated big pay form.');
        }

        $documentXml = $zip->getFromName('word/document.xml');
        if ($documentXml === false) {
            $zip->close();
            throw new RuntimeException('Unable to read big pay form document XML.');
        }

        $updatedXml = $this->fillDocument($documentXml, $project, $contract);
        $zip->addFromString('word/document.xml', $updatedXml);
        $zip->close();

        return [
            'path' => $outputPath,
            'download_name' => $this->buildDownloadName($project, $contract),
        ];
    }

    private function fillDocument(string $documentXml, Project $project, Contract $contract): string
    {
        $document = new DOMDocument();
        $document->preserveWhiteSpace = true;
        $document->formatOutput = false;
        $document->loadXML($documentXml);

        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        $company = $contract->company ?: $project->company;
        $contact = $company?->user;
        $bankAccount = $company?->bankAccounts
            ?->sortByDesc(fn ($account) => (int) $account->is_primary)
            ->first();

        $fields = [
            'contract_code' => (string) ($contract->code ?: ''),
            'company_name' => (string) ($company?->name ?: ''),
            'contact_name' => (string) ($contact?->name ?: ''),
            'contact_phone' => (string) ($company?->telephone ?: $contact?->phone ?: ''),
            'project_name' => (string) $project->name,
            'contract_name' => (string) $contract->name,
            'contract_amount' => $this->formatAmount($contract->amount),
            'additional_contract_amount' => '',
            'fulfillment_deposit' => $this->formatAmount($contract->fulfillmentDeposit),
            'settlement_amount' => '',
            'additional_settlement_amount' => '',
            'payment_basis' => '按合同约定申请支付合同款。                        经办人签字：                年    月    日',
            'paid_amount' => $this->formatAmount($contract->paid_amount),
            'funding_name' => (string) ($contract->funding?->name ?: ''),
            'current_payment' => $this->formatAmount($contract->amount),
            'funding_code' => (string) ($contract->funding?->code ?: ''),
            'amount_uppercase' => $this->toChineseUppercase((float) $contract->amount),
            'bank_name' => (string) ($bankAccount?->name ?: ''),
            'bank_number' => (string) ($bankAccount?->number ?: ''),
        ];

        $this->replaceContractCodeLine($xpath, $fields['contract_code']);
        $this->fillTableCells($xpath, $fields);

        return $document->saveXML();
    }

    private function replaceContractCodeLine(DOMXPath $xpath, string $contractCode): void
    {
        $textNodes = $xpath->query('//w:t');

        foreach ($textNodes as $textNode) {
            $text = $textNode->textContent;
            if (str_contains($text, '合同编号：') && str_contains($text, '单位：元')) {
                $this->setTextNodesValue([$textNode], '合同编号：'.$contractCode.'               单位：元');
                break;
            }
        }
    }

    private function fillTableCells(DOMXPath $xpath, array $fields): void
    {
        $tables = $xpath->query('//w:tbl');
        $table = $tables->item(0);

        if (!$table instanceof DOMElement) {
            return;
        }

        foreach ($xpath->query('./w:tr', $table) as $row) {
            $cells = iterator_to_array($xpath->query('./w:tc', $row));
            $rowTexts = array_map(fn ($cell) => $this->getCellText($xpath, $cell), $cells);
            $normalized = array_map([$this, 'normalizeText'], $rowTexts);

            if (($normalized[0] ?? '') === '收款单位') {
                $this->setCellText($xpath, $cells[1], $fields['company_name']);
                $this->setCellText($xpath, $cells[3], $fields['contact_name']);
                continue;
            }

            if (($normalized[2] ?? '') === '联系电话') {
                $this->setCellText($xpath, $cells[3], $fields['contact_phone']);
                continue;
            }

            if (($normalized[0] ?? '') === '项目名称') {
                $this->setCellText($xpath, $cells[1], $fields['project_name']);
                $this->setCellText($xpath, $cells[3], $fields['contract_name']);
                continue;
            }

            if (($normalized[0] ?? '') === '合同金额') {
                $this->setCellText($xpath, $cells[1], $fields['contract_amount']);
                $this->setCellText($xpath, $cells[3], $fields['additional_contract_amount']);
                $this->setCellText($xpath, $cells[5], $fields['fulfillment_deposit']);
                continue;
            }

            if (($normalized[0] ?? '') === '结算金额') {
                $this->setCellText($xpath, $cells[1], $fields['settlement_amount']);
                $this->setCellText($xpath, $cells[3], $fields['additional_settlement_amount']);
                continue;
            }

            if (($normalized[0] ?? '') === '本次支付依据（项目完成情况或合同支付条款等）') {
                $this->setCellText($xpath, $cells[1], $fields['payment_basis']);
                continue;
            }

            if (($normalized[0] ?? '') === '付款情况') {
                $this->setCellText($xpath, $cells[2], $fields['paid_amount']);
                $this->setCellText($xpath, $cells[4], $fields['funding_name']);
                continue;
            }

            if (($normalized[1] ?? '') === '本期申请付款') {
                $this->setCellText($xpath, $cells[2], $fields['current_payment']);
                $this->setCellText($xpath, $cells[4], $fields['funding_code']);
                continue;
            }

            if (($normalized[1] ?? '') === '人民币大写') {
                $this->setCellText($xpath, $cells[2], $fields['amount_uppercase']);
                $this->setCellText($xpath, $cells[4], $fields['bank_name']);
                continue;
            }

            if (($normalized[3] ?? '') === '银行账号') {
                $this->setCellText($xpath, $cells[4], $fields['bank_number']);
            }
        }
    }

    private function getCellText(DOMXPath $xpath, DOMElement $cell): string
    {
        $parts = [];
        foreach ($xpath->query('.//w:t', $cell) as $textNode) {
            $parts[] = $textNode->textContent;
        }

        return trim(str_replace("\u{3000}", ' ', implode('', $parts)));
    }

    private function setCellText(DOMXPath $xpath, DOMElement $cell, string $value): void
    {
        $textNodes = iterator_to_array($xpath->query('.//w:t', $cell));

        if ($textNodes === []) {
            return;
        }

        $this->setTextNodesValue($textNodes, $value);
    }

    private function setTextNodesValue(array $textNodes, string $value): void
    {
        $firstNode = $textNodes[0];
        $firstNode->nodeValue = $value;

        $needsPreserveSpace = trim($value) !== $value || str_contains($value, '  ');
        if ($needsPreserveSpace) {
            $firstNode->setAttribute('xml:space', 'preserve');
        } else {
            $firstNode->removeAttribute('xml:space');
        }

        foreach (array_slice($textNodes, 1) as $textNode) {
            $textNode->nodeValue = '';
        }
    }

    private function normalizeText(string $value): string
    {
        $value = str_replace("\u{3000}", ' ', $value);

        return preg_replace('/\s+/u', '', trim($value)) ?: '';
    }

    private function formatAmount(null|int|float|string $amount): string
    {
        if ($amount === null || $amount === '') {
            return '';
        }

        $formatted = number_format((float) $amount, 2, '.', '');

        return rtrim(rtrim($formatted, '0'), '.');
    }

    private function buildDownloadName(Project $project, Contract $contract): string
    {
        $projectName = preg_replace('/[\\\\\\/:\*\?"<>\|]+/u', '-', $project->name) ?: 'project';
        $contractName = preg_replace('/[\\\\\\/:\*\?"<>\|]+/u', '-', $contract->name) ?: 'contract';

        return $projectName.'-'.$contractName.'-大额付款审核表.docx';
    }

    public function toChineseUppercase(float $amount): string
    {
        $amount = round($amount, 2);
        $number = sprintf('%.2f', $amount);
        [$integerPart, $decimalPart] = explode('.', $number);

        $digits = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];
        $smallUnits = ['', '拾', '佰', '仟'];
        $largeUnits = ['', '万', '亿', '兆'];

        if ((int) $integerPart === 0) {
            $integerText = '零元';
        } else {
            $integerText = '';
            $groups = [];
            while ($integerPart !== '') {
                array_unshift($groups, substr($integerPart, -4));
                $integerPart = substr($integerPart, 0, -4);
            }

            $zeroBetweenGroups = false;
            foreach ($groups as $index => $group) {
                $group = str_pad($group, 4, '0', STR_PAD_LEFT);
                $groupText = '';
                $zeroPending = false;

                for ($i = 0; $i < 4; $i++) {
                    $digit = (int) $group[$i];
                    $unitIndex = 3 - $i;

                    if ($digit === 0) {
                        $zeroPending = $groupText !== '';
                        continue;
                    }

                    if ($zeroPending) {
                        $groupText .= '零';
                        $zeroPending = false;
                    }

                    $groupText .= $digits[$digit].$smallUnits[$unitIndex];
                }

                if ($groupText !== '') {
                    if (
                        $integerText !== ''
                        && ($zeroBetweenGroups || (int) $group < 1000)
                        && !str_ends_with($integerText, '零')
                    ) {
                        $integerText .= '零';
                    }

                    $integerText .= $groupText.$largeUnits[count($groups) - $index - 1];
                    $zeroBetweenGroups = false;
                } elseif ($integerText !== '') {
                    $zeroBetweenGroups = true;
                }
            }

            $integerText = preg_replace('/^壹拾/u', '拾', rtrim($integerText, '零')).'元';
        }

        $jiao = (int) $decimalPart[0];
        $fen = (int) $decimalPart[1];

        if ($jiao === 0 && $fen === 0) {
            return $integerText.'整';
        }

        $decimalText = '';
        if ($jiao > 0) {
            $decimalText .= $digits[$jiao].'角';
        }

        if ($fen > 0) {
            if ($jiao === 0) {
                $decimalText .= '零';
            }

            $decimalText .= $digits[$fen].'分';
        }

        return $integerText.$decimalText;
    }
}
