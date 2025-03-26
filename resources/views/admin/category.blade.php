@extends('admin.main')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <h1>Categories</h1>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCategoryModal">
                Create New
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Is Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->is_active ? 'Yes' : 'No' }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                    data-target="#editCategoryModal{{ $category->id }}">
                                    Edit
                                </button>

                                <form action="{{ url('api/category/destroy', $category->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ url('api/category/update', $category->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="name">Category Name:</label>
                                                <input type="text" name="name" value="{{ $category->name }}"
                                                    class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="is_active">Is Active:</label>
                                                <input type="checkbox" name="is_active" value="1"
                                                    {{ $category->is_active ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">
                @for ($i = 1; $i <= $total_pages; $i++)
                    <a href="{{ url('api/admin/categories?page=' . $i) }}" 
                       class="page-link {{ $i == $current_page ? 'active' : '' }}">
                        {{ $i }}
                    </a>
                @endfor
            </div>
            <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog"
                aria-labelledby="createCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ url('api/category/store') }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Category Name:</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="is_active">Is Active:</label>
                                    <input type="checkbox" name="is_active" value="1">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
