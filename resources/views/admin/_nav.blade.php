<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link{{ $page === 'items' ? ' active' : '' }}" href="{{ route('admin.items.index') }}">Товары</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'categories' ? ' active' : '' }}" href="{{ route('admin.categories.index') }}">Категории</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'orders' ? ' active' : '' }}" href="{{ route('admin.orders.index') }}">Заказы</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'incomplete' ? ' active' : '' }}" href="{{ route('admin.orders.incomplete') }}">Не заказанные</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'visitors' ? ' active' : '' }}" href="{{ route('admin.visitors.index') }}">Посетители</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'settings' ? ' active' : '' }}" href="{{ route('admin.settings.index') }}">Настройки</a></li>
</ul>
