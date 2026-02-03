<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover table-striped" id="users-table">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">姓名</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">所属公司</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $user->name }}
                        </div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->company?->name ?? '无' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('users.show', $user->id) }}"
                               class="text-blue-600 hover:text-blue-900 transition-colors"
                               title="查看详情">
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user->id) }}"
                               class="text-indigo-600 hover:text-indigo-900 transition-colors"
                               title="编辑">
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete', 'class' => 'inline']) !!}
                            <button type="submit"
                                    class="text-red-600 hover:text-red-900 transition-colors"
                                    title="删除"
                                    onclick="return confirm('确定要删除该用户吗？此操作不可恢复。')">
                                <i class="far fa-trash-alt"></i>
                            </button>
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="card-footer px-6 py-3 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                显示第 <span class="font-medium">{{ $users->firstItem() }}</span> 到 <span class="font-medium">{{ $users->lastItem() }}</span> 条，共 <span class="font-medium">{{ $users->total() }}</span> 条记录
            </div>
            <div>
                @include('adminlte-templates::common.paginate', ['records' => $users])
            </div>
        </div>
    @endif
</div>
