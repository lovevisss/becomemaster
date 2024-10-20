<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>浙江财经大学东方学院分期付款或大额资金支付审核表</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-4 text-center">
    <h1 class="text-2xl font-bold mb-4">浙江财经大学东方学院分期付款或大额资金支付审核表</h1>
    @foreach ($rowData as $sectionData)
        @foreach ($sectionData as $tableData)
            <div class="mx-auto">
                <table class="min-w-full divide-y divide-gray-200 bg-white shadow-md rounded-lg">
                    <thead class="bg-gray-50">
                    <tr>
{{--                        @foreach ($tableData[0] as $header)--}}
{{--                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                {{ $header }}--}}
{{--                            </th>--}}
{{--                        @endforeach--}}
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($tableData as $id=> $row)
                        @if($id == 0)

                        @else
                            <tr>
                                @foreach ($row as $id => $cell)
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{$id}}
                                        {{ $cell }}
                                    </td>
                                @endforeach
                            </tr>
                        @endif

                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endforeach
</div>
</body>
</html>
