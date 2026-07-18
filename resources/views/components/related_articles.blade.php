<div class="row mt-5 position-relative articles-scroll-box">
    <p class="title mt-3">مقالات مرتبط</p>
    <div class="d-flex align-items-center articles-container-scroll pb-4">
        @foreach ($relateds as $related)
            <div class="px-2">
                @component('components.article', ['article' => $related, 'related_class' => true])
                @endcomponent
            </div>
        @endforeach
    </div>
</div>
