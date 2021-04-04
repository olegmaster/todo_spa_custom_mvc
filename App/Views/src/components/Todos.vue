<template>
    <div>
        <h2>Todos</h2>
        <div class="alert alert-secondary" v-if="error" role="alert">
            {{error}}
        </div>
        <form @submit.prevent="addTodo" class="mb-3">
            <div class="form-group mb-4">
                <label for="todo-username">Username</label>
                <input type="text" class="form-control" id="todo-username" placeholder="Title" v-model="todo.username">
                <small id="form-control" class="form-text text-muted">Enter your username</small>
            </div>
            <div class="form-group mb-4">
                <label for="todo-email">Email</label>
                <input type="email" class="form-control" id="todo-email" placeholder="Title" v-model="todo.email">
                <small id="control2" class="form-text text-muted">Enter email</small>
            </div>
            <div class="form-group mb-4">
                <label for="todo-text">Todo Text</label>
                <textarea id="todo-text" class="form-control" placeholder="Text" v-model="todo.text"></textarea>
            </div>
            <div class="form-group mb-4">
                <label for="todo-completed">Is completed</label>
                <input type="checkbox" class="form-control" id="todo-completed" v-model="todo.is_completed">

                <small id="control3" class="form-text text-muted">Is Completed</small>
            </div>
            <button type="submit" class="btn btn-light btn-block ">Save</button>
        </form>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchTodos(pagination.prev_page_url)">Previous</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{
                        pagination.last_page }}</a>
                </li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchTodos(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
        <div class="card card-body mb-2" v-for="todo in todos" v-bind:key="todo.id">
            <h3>{{ todo.username }}</h3>
            <p>{{ todo.email }}</p>
            <p>{{ todo.text }}</p>
            <hr>
            <button @click="editTodo(todo)" class="btn btn-warning">Edit</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                todos: [],
                todo: {
                    id: '',
                    username: '',
                    email: '',
                    text: '',
                    is_completed: ''
                },
                pagination: {},
                edit: false,
                error: ''
            }
        },

        created() {
            this.fetchTodos();
        },

        methods: {
            fetchTodos(page_url) {
                let vm = this;
                page_url = page_url || '/?get_tasks';
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.todos = res.data;
                        //console.log(this.todos);
                        vm.makePagination(res.meta, res.links);
                    })
                    .catch(err => this.error = err);
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };

                this.pagination = pagination;
            },           
            addTodo() {
                if (this.edit === false) {
                    // Add
                    fetch('?add_task', {
                        method: 'post',
                        body: JSON.stringify(this.todo),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if(data.error){
                                this.error = data.error;
                                return;
                            }
                            this.error = "";
                            this.todo.username = "";
                            this.todo.email = "";
                            this.todo.text = "";
                            this.todo.is_completed = 0;
                            alert('Todo Added');
                            this.fetchTodos();
                        })
                        .catch(err => this.error = err);
                } else {
                    fetch('?update_task', {
                        method: 'post',
                        body: JSON.stringify(this.todo),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if(data.error){
                                this.error = data.error;
                                return;
                            }
                            this.error = "";
                            this.todo.username = "";
                            this.todo.email = "";
                            this.todo.text = "";
                            this.todo.is_completed = 0;
                            alert('Todo Updated');
                            this.fetchTodos();
                        })
                        .catch(err => this.error = err);
                }
            },
            editTodo(todo) {
                this.edit = true;
                this.todo.id = todo.id;
                this.todo.username = todo.username;
                this.todo.email = todo.email;
                this.todo.text = todo.text;
                this.todo.is_completed = todo.is_completed;
            }
        }
    }
</script>
