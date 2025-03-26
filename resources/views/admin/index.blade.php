@extends('admin.main')

@section('title', 'Admin Panel')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-3 mt-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4>Jami postlar</h4>
                    <h2>{{ $totalPosts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>Jami like lar</h4>
                    <h2>{{ $totalLikes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4>Jami dislike lar</h4>
                    <h2>{{ $totalDislikes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-4">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h4>Jami ko‘rishlar</h4>
                    <h2>{{ $totalViews }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-start">
        <a href="{{ route('admin.export.user_post_statistics', ['user_id' => Auth::id()]) }}" class="btn btn-success mr-3">Post Statistikalarini Excelga Yuklab Olish</a>
        <a href="{{ route('admin.export.user_info_pdf', ['user_id' => Auth::id()]) }}" class="btn btn-danger">Foydalanuvchi Ma'lumotlarini PDFga Yuklab Olish</a>
    </div>
      
    @if(Auth::user()->hasRole('admin'))
        <div class="mt-3">
            <h2 class="text-center">Authorlarning statistikasi</h2>
            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Foydalanuvchi</th>
                        <th>Postlar soni</th>
                        <th>Like</th>
                        <th>Dislike</th>
                        <th>Ko‘rishlar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $index => $author)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $author->name }}</td>
                            <td>{{ $author->posts_count }}</td>
                            <td>{{ $author->posts->sum('likes') }}</td>
                            <td>{{ $author->posts->sum('dislikes') }}</td>
                            <td>{{ $author->posts->sum('views') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Hozircha hech qanday author yo‘q.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if(Auth::user()->hasRole('author'))
        <div class="mt-5">
            <h2 class="text-center">Sizning postlaringiz</h2>
            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Sarlavha</th>
                        <th>Like</th>
                        <th>Dislike</th>
                        <th>Ko‘rishlar</th>
                        <th>Yaratilgan sana</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $index => $post)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->likes }}</td>
                            <td>{{ $post->dislikes }}</td>
                            <td>{{ $post->views }}</td>
                            <td>{{ $post->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Hozircha hech qanday post yo‘q.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>


@endsection
