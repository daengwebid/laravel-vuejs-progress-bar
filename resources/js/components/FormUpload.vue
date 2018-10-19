<template>
    <div class="row" style="padding-top: 10px">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="javascript:void(0)" @submit.prevent="uploadSubmit" 
                        enctype="multipart/form-data" method="post">
                        <div class="alert alert-success" v-if="message">{{ message[0] }}</div>
                        <div class="form-group">
                            <label for="">Upload file</label>
                            <input type="file" class="form-control" ref="file" name="file" @change="fileUpload($event.target)" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger btn-sm"
                                :disabled="isLoading">{{ isLoading ? 'Loading...':'Upload' }}</button>
                        </div>
                    </form>
                    <div class="progress">
                        <!-- PROGRESS BAR DENGAN VALUE NYA KITA DAPATKAN DARI VARIABLE progressBar -->
                        <div class="progress-bar" role="progressbar" 
                            :style="{width: progressBar + '%'}" 
                            :aria-valuenow="progressBar" 
                            aria-valuemin="0" 
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List File</h3>
                </div>
                <div class="card-body">
                    <img v-for="(row, index) in files" :src="'storage/' + row" :key="index" alt="" width="150px" height="150px" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        data() {
            return {
                progressBar: 0,
                message: '',
                isLoading: false,
                file: '',
                files: []
            }
        },
        created() {
            this.getAllFile()
        },
        methods: {
            getAllFile() {
                axios.get('/api/file')
                .then((response) => {
                    this.files = response.data
                })
            },
            fileUpload(event) {
                this.file = event.files[0]
            },
            uploadSubmit() {
                this.isLoading = true
                this.message = ''
                let formData = new FormData();
                formData.append('file', this.file);

                axios.post('/api/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: function( progressEvent ) {
                        this.progressBar = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total))
                    }.bind(this)
                })
                .then((response) => {
                    setTimeout(() => {
                        this.message = response.data
                        this.isLoading = false
                        this.reset()
                        this.getAllFile()
                    })
                })
            },
            reset() {
                this.$refs.file.value = '';
            }
        }
    }
</script>
