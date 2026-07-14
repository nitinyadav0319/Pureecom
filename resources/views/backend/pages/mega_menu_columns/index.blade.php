@extends('backend.layouts.master')

@section('title')
    {{ localize('Mega Menu Columns') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
<div class="container">
    <h2 class="mb-4">{{ localize('Mega Menu Columns') }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.mega_menu_columns.create') }}" class="btn btn-primary mb-3">{{ localize('Add New Column') }}</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ localize('Title') }}</th>
                <th>{{ localize('Type') }}</th>
                <th>{{ localize('Variation') }}</th>
                <th>{{ localize('Order') }}</th>
                <th>{{ localize('Status') }}</th>
                <th>{{ localize('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($columns as $column)
                <tr>
                    <td>{{ $column->title }}</td>
                    <td>{{ ucfirst($column->type) }}</td>
                    <td>{{ $column->variation->name ?? '-' }}</td>
                    <td>{{ $column->order }}</td>
                    <td>{{ $column->is_active ? localize('Active') : localize('Inactive') }}</td>
                    <td>
                        <a href="{{ route('admin.mega_menu_columns.edit', $column->id) }}" class="btn btn-sm btn-warning">{{ localize('Edit') }}</a>
                        <form action="{{ route('admin.mega_menu_columns.destroy', $column->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ localize('Are you sure?') }}')">{{ localize('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">{{ localize('No Mega Menu Columns Found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
