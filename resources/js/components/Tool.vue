<template>
    <div v-if="!this.panel.fields[0]['mlaId']">
        <form autocomplete="off">

            <div class="mb-8">
                <h1 class="text-90 font-normal text-2xl mb-3">Create MLA Publication</h1>
                <div class="card">

                    <div class="help-text error-text mt-2 text-danger p-4" >
                      <span v-for="error in errors">{{error}} <br></span>
                    </div>
                    <!--<div class="flex border-b border-40">-->
                        <!--<div class="px-8 py-6 w-1/5">-->
                            <!--<label for="active" class="inline-block text-80 pt-2 leading-tight">active</label>-->
                        <!--</div>-->
                        <!--<div class="py-6 px-8 w-1/2">-->
                            <!--<input type="checkbox" class="checkbox mt-2" id="active" name="active">-->
                        <!--</div>-->
                    <!--</div>-->
                    <div class="flex border-b border-40">
                        <div class="px-8 py-6 w-1/5">
                            <label for="sku" class="inline-block text-80 pt-2 leading-tight">Category<span class="text-danger text-sm">*</span></label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <Select2 v-model="category_id" :options="categories" :settings="{ width: '100%' }"  @change="getCategoryAttributes($event)" />
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="px-8 py-6 w-1/5">
                            <label  class="inline-block text-80 pt-2 leading-tight">Attributes<span class="text-danger text-sm">*</span></label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <div class="flex border-b border-40" v-for="category_attribute in category_attributes">
                                <div class="px-8 py-6 w-1/5">
                                    <label  class="inline-block text-80 pt-2 leading-tight">{{category_attribute.name}}<span class="text-danger text-sm">*</span></label>
                                </div>
                                <div class="py-6 px-8 w-1/2">
                                    <input  type="text"
                                           :placeholder="category_attribute.name"
                                            v-model="category_attribute.value_name"
                                           class="w-full form-control form-input form-input-bordered">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex items-center">
                <button type="button" class="btn btn-default btn-primary inline-flex items-center relative" @click="submit"><span class="">
                       Save
                      </span>
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import Select2 from 'v-select2-component';
    export default {
        components: {
            Select2
        },

        props: ['resourceName', 'resourceId', 'panel'],
        data() {
            return {
                categories: null,
                category_id: null,
                category_attributes: [],
                errors: [],
            }
        },
        created(){

        },
        mounted() {
            console.log(this.panel.fields[0]['mlaId'])
            this.getMLACategories()
        },

        methods : {
            getMLACategories(){
                Nova.request().get('/nova-vendor/ml_publication_creation/get-mla-categories').then(response => {
                    this.categories = response.data
                })
            },
            getCategoryAttributes(categoryId)
            {
                this.category_attributes = []
                Nova.request().get('/nova-vendor/ml_publication_creation/get-category-attributes/' + categoryId).then(response => {
                    let self = this
                    response.data.map(function(attribute, key) {
                        if (attribute.value_type === 'string' &&  attribute.tags && (attribute.tags.catalog_required || attribute.tags.required)) {
                            self.category_attributes.push({
                                'id' : attribute.id,
                                'value_name' : null,
                                'name' : attribute.name,
                            })
                        }

                    });
                })
            },

            async submit(){
                    this.errors = []
                    const response = await Nova.request().post('/nova-vendor/ml_publication_creation/create_ml_publication', {
                        publication_id: this.resourceId,
                        category_id: this.category_id,
                        attributes: this.category_attributes
                    })
                    if (response.data.errors){
                        this.errors = response.data.errors
                    }

            }
        }
    }
</script>
<style>
    .select2 .select2-container {
        width: 100% !important;
    }
</style>
