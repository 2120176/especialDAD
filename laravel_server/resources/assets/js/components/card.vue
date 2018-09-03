<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>
        <router-link class="btn btn-warning" :to="{path: '/adminMasterPage' }">
            Go Back
        </router-link>
        <p>&nbsp;</p>
        <div  v-show="showSuccess"class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>{{ successMessage }}</strong>
        </div>
        <div  v-show="showError"class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>{{ error }}</strong>
        </div>

        <card-list :decks="decks" :cards="cards" @delete-click="deleteCard" @delete-deck="deleteDeck" @message="childMessage" ref="cardsListRef"></card-list>

    </div>
</template>

<script type="text/javascript">
    import CardList from './cardList.vue';

    export default {
        data: function(){
            return {
                title: 'Manage Decks',
                showSuccess: false,
                successMessage: '',
                showError: false,
                error: '',
                cards: [],
                decks: []
            }
        },
        methods: {

            deleteCard: function(card){
                axios.delete('api/cards/'+card.id)
                    .then(response => {
                        this.showSuccess = true;
                        this.successMessage = 'Card Deleted';
                        this.getCards();
                    }).catch((error) => {
                    console.log(response);
                    this.successMessage = null;
                    this.error = error.response.data.msg;
                });
            },

            getCards: function(){
                axios.get('api/cards')
                    .then(response=> {
                        //console.log(response);
                        this.cards = response.data.data;
                    });
            },

            getDecks: function(){
                axios.get('api/deck')
                    .then(response=> {
                        this.decks = response.data.data;
                    });
            },

            deleteDeck:function (deck) {
                axios.delete('api/deck/'+deck.id)
                    .then(response => {
                        console.log(response);
                        this.showSuccess = true;
                        this.successMessage = response.data.msg;
                        this.getDecks();

                    }).catch((error) => {
                        console.log(response);
                        this.showError = true;
                        this.error = error.response.data.msg;
                });
            },

            childMessage: function(message){
                this.showSuccess = true;
                this.successMessage = message;
            },

        },
        components: {
            'card-list': CardList,
        },
        mounted() {
            this.getDecks();
            this.getCards();
        }

    }
</script>

<style scoped>
    p {
        font-size: 2em;
        text-align: center;
    }
</style>