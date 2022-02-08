<header class="header">
	<div class="header__links">
		<a href="{{ route('index') }}" target="_blank" class="header__link">Website</a>
	</div>
	<a href="{{ route('logout') }}" class="header__link header__link_exit js-logout">
		<i class="header__ico fa fa-sign-out-alt" aria-hidden="true"></i>
		Exit
	</a>
	<form action="{{ route('logout') }}" method="POST" class="logout-form">@csrf</form>
</header>
