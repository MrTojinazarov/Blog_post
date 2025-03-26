@extends('admin.main')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <h1>Posts</h1>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPostModal">
                Create New Post
            </button>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 10%;">Category</th>
                <th style="width: 15%;">Title</th>
                <th style="width: 15%;">Description</th>
                <th style="width: 20%;">Text</th>
                <th style="width: 15%;">Image</th>
                <th style="width: 5%;">Likes</th>
                <th style="width: 5%;">Dislikes</th>
                <th style="width: 5%;">Views</th>
                <th style="width: 10%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->category->name }}</td>
                    <td>{{ $post->title }}</td>
                    <td>
                        <span class="description-preview">{{ Str::limit($post->description, 100) }}</span>
                        @if (strlen($post->description) > 100)
                            <a href="#" class="show-more" data-full-text="{{ $post->description }}">more</a>
                            <a href="#" class="show-less" style="display: none;">less</a>
                        @endif
                    </td>
                    <td>
                        <span class="text-preview">{{ Str::limit($post->text, 100) }}</span>
                        @if (strlen($post->text) > 100)
                            <a href="#" class="show-more" data-full-text="{{ $post->text }}">more</a>
                            <a href="#" class="show-less" style="display: none;">less</a>
                        @endif
                    </td>
                    <td>
                        <div id="carousel{{ $post->id }}" class="carousel slide" data-bs-interval="false">
                            <div class="carousel-inner" style="max-width: 150px; max-height: 150px; overflow: hidden;">
                                @foreach ($post->media as $key => $media)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $media->url) }}" class="d-block w-100"
                                            style="max-width: 150px; max-height: 150px; object-fit: cover;"
                                            alt="{{ $post->title }}">
                                    </div>
                                @endforeach
                            </div>

                            @if (count($post->media) > 1)
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carousel{{ $post->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carousel{{ $post->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    </td>
                    <td>{{ $post->likes }}</td>
                    <td>{{ $post->dislikes }}</td>
                    <td>{{ $post->views }}</td>
                    <td>
                        <button type="button" style="width: 90px;" class="btn btn-warning" data-toggle="modal"
                            data-target="#editPostModal{{ $post->id }}">
                            Update
                        </button>

                        <form action="{{ url('api/post/destroy', $post->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="width: 90px;" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>

                <div class="modal fade" id="editPostModal{{ $post->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="editPostModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ url('api/post/update', $post->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="title">Post Title:</label>
                                        <input type="text" name="title" value="{{ $post->title }}"
                                            class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description:</label>
                                        <textarea name="description" class="form-control" rows="3" required>{{ $post->description }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="text">Text:</label>
                                        <textarea name="text" class="form-control" rows="3" required>{{ $post->text }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="category_id">Category:</label>
                                        <select name="category_id" class="form-control" required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="media">Upload New Media</label>
                                        <input type="file" class="form-control" name="media[]" id="media"
                                            multiple>
                                    </div>

                                    <div class="form-group">
                                        <label>Current Media</label>
                                        <div class="d-flex flex-wrap">
                                            @foreach ($post->media as $key => $media)
                                                <div class="position-relative m-2">
                                                    @if (str_contains($media->media_type, 'image'))
                                                        <img src="{{ asset('storage/' . $media->url) }}" class="d-block"
                                                            style="max-width: 150px;" alt="{{ $post->title }}">
                                                    @elseif(str_contains($media->media_type, 'video'))
                                                        <video src="{{ asset('storage/' . $media->url) }}" controls
                                                            style="max-width: 150px;"></video>
                                                    @endif
                                                    <div>
                                                        <input type="checkbox" name="delete_media[]"
                                                            value="{{ $media->id }}"> Delete
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            <a href="{{ url('api/admin/posts?page=' . $i) }}" 
               class="page-link {{ $i == $current_page ? 'active' : '' }}">
                {{ $i }}
            </a>
        @endfor
    </div>    
    <div class="modal fade" id="createPostModal" tabindex="-1" role="dialog" aria-labelledby="createPostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPostModalLabel">Create New Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('api/post/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Post Title:</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="text">Text:</label>
                            <textarea name="text" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="media">Images/Videos:</label>
                            <div id="media-container">
                                <input type="file" name="media[]" class="form-control" required>
                            </div>
                            <button type="button" class="btn btn-success mt-2" id="add-media">Add +</button>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Category:</label>
                            <select name="category_id" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
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
@endsection
