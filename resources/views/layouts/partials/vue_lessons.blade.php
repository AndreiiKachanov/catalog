@php
    $page = Route::getCurrentRoute()->getName();
@endphp

<div class="p-3 vue-menu">
    <div class="container">
        <p>Vue lessons</p>
        <div class="row">
            <div class="col"><a class="nav-link{{ $page === 'vue.form' ? ' active' : '' }}" href=" {{ route('vue.form')  }}">Form</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.hooks' ? ' active' : '' }}" href=" {{ route('vue.hooks')  }}">Hooks</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.refs' ? ' active' : '' }}" href=" {{ route('vue.refs')  }}">Refs</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.classes' ? ' active' : '' }}" href=" {{ route('vue.classes')  }}">Classes</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.styles' ? ' active' : '' }}" href=" {{ route('vue.styles')  }}">Styles</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.scroll' ? ' active' : '' }}" href=" {{ route('vue.scroll')  }}">Scroll</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.reg_form' ? ' active' : '' }}" href=" {{ route('vue.reg_form')  }}">Reg Form</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.todo' ? ' active' : '' }}" href=" {{ route('vue.todo')  }}">To Do List</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.component' ? ' active' : '' }}" href=" {{ route('vue.component')  }}">Component</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.component2' ? ' active' : '' }}" href=" {{ route('vue.component2')  }}">Component 2</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.props_validate' ? ' active' : '' }}" href=" {{ route('vue.props_validate')  }}">Props validate</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.reg_form_font_awesome_vue' ? ' active' : '' }}" href=" {{ route('vue.reg_form_font_awesome_vue')  }}">Reg form font awesome vue</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.slots' ? ' active' : '' }}" href=" {{ route('vue.slots')  }}">Slots</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.transition' ? ' active' : '' }}" href=" {{ route('vue.transition')  }}">Transition</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.transition_dz' ? ' active' : '' }}" href=" {{ route('vue.transition_dz')  }}">Transition DZ</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.vuex' ? ' active' : '' }}" href=" {{ route('vue.vuex')  }}">Vuex Bad</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.vuex_norm' ? ' active' : '' }}" href=" {{ route('vue.vuex_norm')  }}">Vuex Norm</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.vuex_dz' ? ' active' : '' }}" href=" {{ route('vue.vuex_dz')  }}">Vuex Dz</a></div>
            <div class="col"><a class="nav-link{{ $page === 'vue.router' ? ' active' : '' }}" href=" {{ route('vue.router')  }}">Vue router</a></div>
        </div>
    </div>
</div>