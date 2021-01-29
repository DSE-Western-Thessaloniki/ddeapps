<template>
    <div class="container">
        <div class="form-group row">
            <label for="title" class="col-3 col-form-label">Title</label>
            <div class="col-9 align-self-center">
                <input type="text" class="form-control" name="title" id="title" v-model="dtitle" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="image" class="col-3 col-form-label">Image</label>
            <div class="col-9 align-self-center">
                <input type="text" class="form-control" name="image" id="image" v-model="dlogofile">
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
                <textarea class="form-control text-center" name="doclogotext" rows="10" id="text" v-model="dtext"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="active" class="col-3 col-form-label">Active</label>
            <div class="col-9">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="active" id="active" value="1" v-model="dactive">
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            title: String,
            logofile: {
                default: "logo.jpg",
                type: String,
            },
            text: String,
            active: {
                default: true,
                type: Boolean
            },
        },
        mounted() {
        },
        data: function() {
            return {
                dtitle: this.title,
                dlogofile: this.logofile,
                dtext: this.text,
                dactive: this.active,
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
