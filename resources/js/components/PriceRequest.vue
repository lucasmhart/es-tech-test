<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Price request</div>

                    <div class="card-body">
                        <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                        <div v-for="(sku, index) in sku_list"
                            :key="`skuInput-${index}`"
                            class="input-group mb-3">
                          <input type="text"
                            class="form-control"
                            name="sku"
                            id="sku"
                            aria-describedby="helpId"
                            placeholder="Product sku"
                            v-model="sku.sku">

                            <div v-show="sku_list.length > 1" @click="removeSku(index)" class="input-group-append">
                                <button class="btn btn-danger" type="button">Remove</button>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="button" name="add-sku" id="add-sku" class="btn btn-primary btn-sm" v-on:click="addSku">+</button>
                        </div>

                        <div class="form-group">
                          <label for="account_id">Account Id</label>
                          <input type="text"
                            class="form-control" name="account_id" id="account_id" aria-describedby="helpId" placeholder="Type the account id" v-model="account_id">
                        </div>
                        <button type="button" name="request-button" id="request-button" class="btn btn-success btn-lg btn-block" v-on:click="getPrice">Request price</button>

                        <ul v-show="prices.length > 0">
                            <li v-for="price in prices">{{ price.sku }} - {{ price.price }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                sku_list        : [{sku: ''}],
                account_id      : null,
                prices          : [],
                validationErrors: []
            }
        },
        methods: {
            addSku() {
                this.sku_list.push({ sku: '' })
            },
            removeSku(index) {
                this.sku_list.splice(index, 1)
            },

            getPrice() {
                this.validationErrors = []
                this.prices = []

                var skus = this.sku_list.map((sku) => sku.sku)
                    .filter((sku) => sku.trim() !== '')

                axios.post('price/get', {
                    sku_list  : skus,
                    account_id: this.account_id
                }).then(response => {
                    this.prices = response.data.result
                }).catch(error  => {
                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors
                    }
                })
            }
        }
    }
</script>
