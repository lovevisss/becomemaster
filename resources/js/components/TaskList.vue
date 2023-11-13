<template>
    <div>
        <h1>Task List</h1>
        <ul>
            <li v-for="task in tasks" :key="task.id">
                {{ task.description }}
            </li>
        </ul>

        <input type="text" v-model="newTask" @blur="addTask" @keydown="tapParticipants">
        <span v-if="activePeer" v-text="activePeer.name + ' is typing...'"></span>
    </div>
</template>


<script>
    export default {
        props: ['project','user'],
        data() {
            return {
                project: this.project,
                tasks: [],
                newTask: '',
                activePeer: false
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
                })
                .listenForWhisper('typing', (e) => {
                    this.activePeer = e;

                   
                });
        },

        methods: {
            addTask() {
                this.activePeer = false;
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
            },

            tapParticipants(e) {
                window.Echo.private('tasks.' + this.project.id)
                    .whisper('typing', {
                        name: 'John Doe'
                    });
            }
        }
    }   
</script>