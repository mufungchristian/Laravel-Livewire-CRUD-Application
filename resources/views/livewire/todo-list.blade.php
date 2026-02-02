<div>
    @if (session('error'))
        <h2>{{ session('error') }}</h2>
    @endif
    @include('livewire.includes.create-todo')
    @include('livewire.includes.search-box')
    <div id="todos-list">
        @foreach ($todos as $todo)
            @include('livewire.includes.todo-card')
        @endforeach

        <div class="my-2">
            <!-- Pagination goes here -->
            {{ $todos->links() }}
        </div>
    </div>
</div>
