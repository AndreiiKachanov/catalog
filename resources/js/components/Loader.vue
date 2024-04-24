<template>
    <div class="loader-container" v-if="isLoading">
        <div class="loader"></div>
    </div>
</template>

<script>
export default {
    props: {
        isLoading: Boolean
    }
};
</script>

<style>
.loader-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}

.loader {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.rainbow-loader {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 4px solid transparent;
    border-top-color: #f06d06;
    animation: spin 2s linear infinite, changeColor 6s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

@keyframes changeColor {
    0% {
        border-top-color: #f06d06;
    }
    12.5% {
        border-top-color: #f0a306;
    }
    25% {
        border-top-color: #f0d506;
    }
    37.5% {
        border-top-color: #a5f006;
    }
    50% {
        border-top-color: #4cf006;
    }
    62.5% {
        border-top-color: #06f0a3;
    }
    75% {
        border-top-color: #069ff0;
    }
    87.5% {
        border-top-color: #065af0;
    }
    100% {
        border-top-color: #f006ec;
    }
}
</style>
