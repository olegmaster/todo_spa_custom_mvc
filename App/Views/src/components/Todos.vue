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
                <input type="checkbox" id="todo-completed" v-model="todo.is_completed">
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
                <li>
                    <button type="button" class="btn" @click="orderBy('username')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-up" viewBox="0 0 16 16">
                            <path d="M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"></path>
                        </svg>
                        Sort by Username
                    </button>
                </li>
                <li>
                    <button type="button" class="btn" @click="orderBy('email')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-up" viewBox="0 0 16 16">
                            <path d="M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"></path>
                        </svg>
                        Sort by Email
                    </button>
                </li>
                <li>
                    <button type="button" class="btn" @click="orderBy('email')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-up" viewBox="0 0 16 16">
                            <path d="M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"></path>
                        </svg>
                        Sort by Text
                    </button>
                </li>
                <li>
                    <button type="button" class="btn"  @click="orderBy('is_completed')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-up" viewBox="0 0 16 16">
                            <path d="M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"></path>
                        </svg>
                        Sort by Is Completed
                    </button>
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
                error: '',
                sort: {
                    attribute: 'id',
                    direction: 1
                }
            }
        },

        created() {
            this.fetchTodos();
        },

        methods: {
            fetchTodos(page_url, sort_order = 'id', sort_direction = 1) {
                let vm = this;

                if(sort_order){
                    this.sort.attribute = sort_order;
                }

                if(sort_direction){
                    this.sort.direction = sort_direction;
                }

                let sortDirection = this.sort.direction === 1 ? 'DESC' : 'ASC';

                page_url = page_url || '/?get_tasks';

                    if(page_url === '/?get_tasks'){
                        page_url += '&page=' + (this.pagination.current_page || 1);
                    }
                    page_url += '&order_by=' + sort_order + '&order_direction=' + sortDirection;


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
            },
            orderBy(attribute){
                if(this.sort.attribute === attribute){
                    this.sort.direction = this.sort.direction * -1;
                } else {
                    this.sort.attribute = attribute;
                    this.sort.direction = 1;
                }

                this.fetchTodos( null , this.sort.attribute, this.sort.direction)
            }
        }
    }
</script>
