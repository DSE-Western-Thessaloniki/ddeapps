<template>
    <div class="container">
        <div class="form-group row">
            <label for="title" class="col-3 col-form-label">Title</label>
            <div class="col-9 align-self-center">
                <input type="text" class="form-control" name="doclogotitle" id="title">
            </div>
        </div>

        <div class="form-group row">
            <label for="image" class="col-3 col-form-label">Image</label>
            <div class="col-9 align-self-center">
                <input type="text" class="form-control" name="image" id="image" v-model="logofile">
            </div>
        </div>

        <div class="form-group row">
            <label for="imagepreview" class="col-3 col-form-label">Image preview:</label>
            <div class="col-9 align-self-center">
                <img id="imagepreview" v-bind:src="logofullpath">
            </div>
        </div>

        <div class="form-group row">
            <label for="text" class="col-3 col-form-label">Text</label>
            <div class="col-9 align-self-center">
                <textarea class="form-control text-center" name="doclogotext" rows="10" id="text"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="active" class="col-3 col-form-label">Active</label>
            <div class="col-9">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="active" id="active" value="1" checked="checked">
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
        },
        mounted() {
            console.log('DocLogoForm mounted.')
        },
        data: function() {
            return {
                logofile: 'logo.jpg'
            }
        },
        methods: {
        },
        computed: {
            logofullpath: function() {
                return '/images/'+this.logofile
            }
        }
    }
</script>
