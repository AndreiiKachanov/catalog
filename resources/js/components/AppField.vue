<template>
    <div class="form-group">
        <label class="mr-1" :for="name">{{ label }}</label>
        <transition v-if="activated" name="icon" mode="out-in" appear appear-active-class="icon-appear-active">
            <fa-icon :icon="icon" :key="icon" :class="iconClasses"></fa-icon>
        </transition>
        <input
            :id="name"
            type="text"
            class="form-control"
            :class="inputClasses"
            :value="value"
            :name="name"
        >
    </div>
</template>

<script>
import {library} from '@fortawesome/fontawesome-svg-core'
import {FontAwesomeIcon as FaIcon} from '@fortawesome/vue-fontawesome'
import {faCircleExclamation, faCheckCircle} from '@fortawesome/free-solid-svg-icons'

library.add(faCircleExclamation, faCheckCircle);

export default {
    name: "AppField",
    components: {
        FaIcon
    },
    props: {
        label: String,
        name: String,
        value: String,
        valid: Boolean,
        activated: Boolean
    },
    computed: {
        icon() {
            return this.valid ? 'check-circle' : 'exclamation-circle';
        },
        iconClasses() {
            return this.valid ? 'text-success' : 'text-danger';
        },
        inputClasses() {
            if (this.activated) {
                return this.valid ? 'is-valid' : 'is-invalid';
            }
        }
    }
}
</script>

<style lang="scss">
.icon-enter-active {
    animation: iconIn 0.3s;
}

.icon-leave-active {
    animation: iconOut 0.3s;
}

.icon-appear-active {
    animation: iconFade 0.3s;
}

@keyframes iconIn {
    from {
        transform: rotateY(-90deg)
    }
    to {
        transform: rotateY(0deg)
    }
}

@keyframes iconOut {
    from {
        transform: rotateY(0deg)
    }
    to {
        transform: rotateY(90deg)
    }
}

@keyframes iconFade {
    from {
        opacity: 0
    }
    to {
        opacity: 1
    }
}
</style>
