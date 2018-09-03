<template>
    <div>
        <form method="post" v-on:submit.prevent="createDeck">
            <h3><strong>Create a deck</strong></h3>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label col-form-label-lg text-right">Name *</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Name of the deck" v-model="deck.name" required>
                </div>
            </div>
            <div class="btn-group pull-right">
                <button type="button" class="btn btn-primary" v-on:click="cancel">Cancel</button>
                <button type="submit" class="btn btn-success pull-right">Create</button>
            </div>
        </form>
        <p></p>
        <p></p>
        <h4><strong>Decks</strong></h4>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Hidden Face</th>
                <th>Name</th>
                <th>Id</th>
                <th>Active</th>
                <th>Complete</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="deck in decks"  :key="deck.id">
                <td><img v-bind:src="deck.hidden_face_image_path" /></td>
                <td>{{ deck.name }}</td>
                <td>{{ deck.id }}</td>
                <td v-if="deck.active === 1 ">Yes</td>
                <td v-else>No</td>
                <td v-if="deck.complete === 1 ">Yes</td>
                <td v-else>No</td>
                <td>
                    <a class="btn btn-xs btn-danger" v-on:click.prevent="deleteDeck(deck)">Delete</a>
                </td>
            </tr>
            </tbody>
        </table>
        <p></p>
        <p></p>
        <div class="row">
            <h3><strong>Upload a card image</strong></h3>
            <div class="col-md-12">
                <div class="col-md-2">
                    <label class="control-label" for="valueOptions">Value</label>
                    <select v-model="value" name="valueOptions" id="valueOptions" class="form-control">
                        <option v-for="option in valueOptions" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label" for="suiteOptions">Suite</label>
                    <select v-model="suite" name="suiteOptions" id="suiteOptions" class="form-control">
                        <option v-for="option in suiteOptions" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label" for="deck">Deck</label>
                    <select v-model="deck_id" name="deck" id="deck" class="form-control">
                        <option v-for="deck in decks">
                            {{ deck.id }}
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="file" v-on:change="onFileChange" class="form-control">
                </div>
                <div class="col-md-2">
                    <img :src="card" class="img-responsive">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success btn-block" v-model="card" v-on:click.prevent="uploadCard(card)">
                        Upload
                    </button>
                </div>
            </div>
        </div>
        <p></p>
        <p></p>
        <h4><strong>Cards</strong></h4>
        <div  v-show="showSuccess"class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>{{ successMessage }}</strong>
        </div>
        <div  v-show="showError"class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>{{ error }}</strong>
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Image</th>
                <th>Value</th>
                <th>Suite</th>
                <th>Deck Id</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="card in cards"  :key="card.id">
                <td><img v-bind:src="card.path" /></td>
                <td>{{ card.value }}</td>
                <td>{{ card.suite }}</td>
                <td>{{ card.deck_id}}</td>
                <td>
                    <a class="btn btn-xs btn-danger" v-on:click.prevent="deleteCard(card)">Delete</a>
                </td>
            </tr>
            </tbody>
        </table>



    </div>
</template>

<script type="text/javascript">

    module.exports={
        props: ['decks','cards'],
        data: function(){
            return {
                card: '',
                value: '',
                suite: '',
                deck_id: '',
                deck: {
                    name:'',
                    hidden_face_image_path:'',
                },
                valueOptions: [
                    {text: 'Hidden', value: 'Hidden'},
                    {text: 'Ace', value: 'Ace'},
                    {text: '2', value: 2},
                    {text: '3', value: 3},
                    {text: '4', value: 4},
                    {text: '5', value: 5},
                    {text: '6', value: 6},
                    {text: '7', value: 7},
                    {text: 'Jack', value: 'Jack'},
                    {text: 'Queen', value: 'Queen'},
                    {text: 'King', value: 'King'}
                ],
                suiteOptions: [
                    {text: 'None', value: 'None'},
                    {text: 'Club', value: 'Club'},
                    {text: 'Diamond', value: 'Diamond'},
                    {text: 'Heart', value: 'Heart'},
                    {text: 'Spade', value: 'Spade'}
                ],
                error: null,
                showError: false,
                successMessage: '',
                showSuccess: false

            }
        },
        methods: {
            deleteCard: function (card) {
                this.$emit('delete-click', card);
            },

            onFileChange: function(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createCard(files[0]);
            },
            createCard: function (file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.card = e.target.result;
                    console.log("O PATH NAO ESTA VAZIO ", vm.card);
                };
                reader.readAsDataURL(file);
            },
            uploadCard: function(card){
                axios.post('/api/cards', {card: this.card, value: this.value, suite: this.suite, deck_id: this.deck_id})
                    .then(response => {
                        this.card = '';
                        this.$parent.getCards();
                        this.$parent.getDecks();
                        this.showSuccess = true;
                        this.successMessage = response.data.msg;
                    }).catch((error) => {
                    this.showError = true;
                    this.error = error.response.data.msg;
                });
            },

            createDeck: function () {
                axios.post('api/deck', {name: this.deck.name})
                    .then(response => {
                        this.$parent.showSuccess = true;
                        this.$parent.successMessage = 'Deck Created!';
                        this.$parent.getDecks();
                    });
            },

            deleteDeck: function (deck) {
                this.$emit('delete-deck', deck);
            },

            getCards: function(){
                axios.get('api/cards')
                    .then(response=> {
                        //console.log(response);
                        this.cards = response.data.data;
                    });
            },


            cancel(){
                this.deck.name = '';
            },
        },
    }
</script>

<style scoped>
    tr.activerow {
        background: #123456  !important;
        color: #fff          !important;
    }
    img{
        max-height: 36px;
    }

</style>