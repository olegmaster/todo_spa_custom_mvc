<template>
    <div>
        <h2>Todo Application</h2>
        <router-link to="/login">login</router-link>
        <hr>
        <TodoList
        />
        <p v-else>No todo</p>
    </div>
</template>

<script>
    import TodoList from '@/components/Todos'

    export default {
        name: 'app',
        components: {
            TodoList: TodoList,
        }
    }
</script>