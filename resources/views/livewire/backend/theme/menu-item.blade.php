<li>
    <a href="{{ $hasSubMenu ? 'javascript: void(0);' : $this->getUrl() }}" class="{{ $hasSubMenu ? 'has-arrow' : '' }} waves-effect" {{ $hasSubMenu ? '' : 'wire:navigate' }}>
        <i class="{{ $icon }}"></i>
        <span>{{ $label }}</span>
    </a>

    @if($hasSubMenu)
    <ul class="sub-menu" aria-expanded="false">
        @foreach($subMenuItems as $item)
        <li><a href="{{ route($item['url']) }}" wire:navigate>{{ $item['label'] }}</a></li>
        @endforeach
    </ul>
    @endif
</li>