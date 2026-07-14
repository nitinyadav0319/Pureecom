@extends('backend.layouts.master')

@section('title')
    {{ localize('Mega Menu Columns') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <div class="card">
        <div class="card-body">
            <form
                action="{{ isset($column) ? route('admin.mega_menu_columns.update', $column->id) : route('admin.mega_menu_columns.store') }}"
                method="POST">

                @csrf
                @if(isset($column))
                    @method('PUT')
                @endif

                {{-- Categories --}}
                <div class="form-group">
                    <label>{{ localize('Select Categories') }}</label>
                    <select name="category_ids[]" class="form-control" multiple required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @if(isset($column) && $column->categories->contains($cat->id))
                            selected @endif>
                                {{ $cat->collectLocalization('name') }}
                            </option>
                            @foreach($cat->childrenCategories as $subCat)
                                <option value="{{ $subCat->id }}" @if(isset($column) && $column->categories->contains($subCat->id))
                                selected @endif>
                                    └ {{ $subCat->collectLocalization('name') }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                {{-- Title --}}
                <div class="form-group">
                    <label>{{ localize('Title') }}</label>
                    <input type="text" name="title" class="form-control" value="{{ $column->title ?? '' }}" required>
                </div>

                {{-- Column Type --}}
                <div class="form-group">
                    <label>{{ localize('Column Type') }}</label>
                    <select name="type" class="form-control" id="column-type" required>
                        <option value="variation" @if(isset($column) && $column->type == 'variation') selected @endif>
                            Variation</option>
                        <option value="brand" @if(isset($column) && $column->type == 'brand') selected @endif>Brand</option>
                        <!-- <option value="category" @if(isset($column) && $column->type=='category') selected @endif>Category/Subcategory</option> -->
                    </select>
                </div>

                {{-- Variation --}}
                <div class="form-group type-dependent" id="variation-select" style="display:none;">
                    <label>{{ localize('Select Variation') }}</label>
                    <select name="variation_id" class="form-control">
                        <option value="">{{ localize('Select') }}</option>
                        @foreach($variations as $variation)
                            <option value="{{ $variation->id }}" @if(isset($column) && $column->variation_id == $variation->id)
                            selected @endif>{{ $variation->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Brand --}}
                <div class="form-group type-dependent" id="brand-select" style="display:none;">
                    <label>{{ localize('Select Brand') }}</label>
                    <select name="brand_id" class="form-control">
                        <option value="">{{ localize('Select') }}</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" @if(isset($column) && $column->brand_id == $brand->id) selected
                            @endif>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Order --}}
                <div class="form-group">
                    <label>{{ localize('Order') }}</label>
                    <input type="number" name="order" class="form-control" value="{{ $column->order ?? 0 }}">
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label>{{ localize('Status') }}</label>
                    <select name="is_active" class="form-control">
                        <option value="1" @if(isset($column) && $column->is_active) selected @endif>Active</option>
                        <option value="0" @if(isset($column) && !$column->is_active) selected @endif>Inactive</option>
                    </select>
                </div>

                <button type="submit"
                    class="btn btn-primary">{{ isset($column) ? localize('Update') : localize('Create') }}</button>
            </form>
        </div>
    </div>

    {{-- JS for show/hide based on type --}}
    <script>
        function toggleTypeFields() {
            const type = document.getElementById('column-type').value;
            document.getElementById('variation-select').style.display = type === 'variation' ? 'block' : 'none';
            document.getElementById('brand-select').style.display = type === 'brand' ? 'block' : 'none';
            document.getElementById('category-select').style.display = type === 'category' ? 'block' : 'none';
        }

        document.getElementById('column-type').addEventListener('change', toggleTypeFields);
        window.addEventListener('DOMContentLoaded', toggleTypeFields);
    </script>
@endsection