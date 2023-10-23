<template>
    <div>
        <h1>Task List</h1>
        <ul>
            <li v-for="task in tasks" :key="task.id">
                {{ task.description }}
            </li>
        </ul>

        <input type="text" v-model="newTask" @blur="addTask">
    </div>
</template>


<script>
    export default {
        props: ['project'],
        data() {
            return {
                project: this.project,
                tasks: [],
                newTask: ''
            }
        },
        mounted() {
            axios.get('/tasks')
                .then(response => {
                    this.tasks = response.data;
                });

            axios.get('/projects/' + this.project.id)
                .then(response => {
                    this.project = response.data;
                });
            window.Echo.private('tasks.' + this.project.id)
                .listen('TaskCreated', (e) => {
                    this.tasks.push(e.task);
                });
        },

        methods: {
            addTask() {
                axios.post(`/api/projects/${this.project.id}/tasks`, {
                    description: this.newTask,
                    name: 'John Doe',
                    user_id: 1
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    this.tasks.push(response.data);
                    this.newTask = '';
                });
            }
        }
    }   
</script>