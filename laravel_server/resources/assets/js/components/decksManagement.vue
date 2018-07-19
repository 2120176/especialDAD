<template>
	<div class="jumbotron">
        <div class="deckBtn"> 
            <a class="btn btn-primary" @click.prevent="createDeck()">Criar Deck</a> -
        </div>


		<div v-if="isCreating">
			<div class="control-group">
				<label class="control-label" for="name"> Name </label>
				<div class="controls">
					<input type="text" v-model="deck.name" id="name" name="name" class="input-large">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="path"> Hidden Image Path </label>
				<div class="controls">
					<input type="text" v-model="deck.hidden_face_image_path" id="path" name="path" class="input-large">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="path"> Active </label>
				<div class="controls">
					<input type="checkbox" v-model="deck.active" id="active" value="1" class="input-large">
				</div>
			</div>


			<div class="form-group">
				<a class="btn btn-default" v-on:click.prevent="saveDeck()">Save</a>
				<a class="btn btn-default" v-on:click.prevent="cancelEdit()">Cancel</a>
	    	</div>

		</div>


		<div v-if="isEdit">
			<div class="control-group">
				<label class="control-label" for="name"> Name </label>
				<div class="controls">
					<input type="text" v-model="deck.name" id="name" name="name" class="input-large">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="path"> Hidden Image Path </label>
				<div class="controls">
					<input type="text" v-model="deck.hidden_face_image_path" id="path" name="path" class="input-large">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="path"> Active </label>
				<div class="controls">
					<input type="checkbox" v-model="deck.active" id="active" value="1" class="input-large">
				</div>
			</div>


			<div class="form-group">
				<a class="btn btn-default" v-on:click.prevent="editDeck()">Save</a>
				<a class="btn btn-default" v-on:click.prevent="cancelEdit()">Cancel</a>
	    	</div>

		</div>

		<table class="table table-striped">
			<thead>
			<tr>
				<th><strong>Name</strong></th>
				<th><strong>Hide Face</strong></th>
				<th><strong>Active</strong></th>
				<th><strong>Complete</strong></th>
				<th><strong>Actions</strong></th>
			</tr>
			</thead>
			<tbody>
			
			<tr v-for="deck in decks"  :key="deck.id">
				<td>{{ deck.name }}</td>
				<td><img v-bind:src="pieceImageURL(deck.hidden_face_image_path)" height="100" width="60"> </td>
				<td>{{ deck.active }}</td>
				<td>{{ deck.complete }}</td>
				<td>
					<a class="btn btn-xs btn-warning" v-on:click.prevent="showEdit(deck)">Edit Deck</a>
					<a class="btn btn-xs btn-danger" v-on:click.prevent="deleteDeck(deck)">Delete Deck</a>
				</td>
				
			</tr>
			</tbody>
			
		</table>


	    
	</div>
</template>

<script type="text/javascript">
	module.exports={
		data: function() {
			return {
				isCreating: false,
				deck : {
					name : null,
					hidden_face_image_path: null,
					active: 0,
				},
				decks: {},
				isEdit: false,
			};
		},
	    methods: {
			showEdit: function (deck) {
				this.deck = deck;
				this.isEdit = true;
			},
			editDeck: function () {
				axios.put('api/decks/'+this.deck.id, this.deck)
	                .then(response =>{
	                	// Copy object properties from response.data.data to this.user
						// without creating a new reference
						Object.assign(this.deck, response.data);
						this.isEdit = false;
					}).catch(error => {						
						console.log('error', error);
					});
			},
	        saveDeck: function (deck) {
				if (this.deck.name == null || this.deck.hidden_face_image_path == null) {
					alert("Nome/Caminho inválido(s)!");
				} else {
					axios.post('api/decks', this.deck)
						.then(response => {
							Object.assign(this.deck, response.data.data);
							
						}).then(response => {
							this.isCreating = false;
							this.getDecks();
						}).catch (error => {
							console.log('saveDeck catch: ', error);
						});
				}
				
			},
			createDeck: function () {
				this.isCreating = true;
				this.deck.name = null;
				this.deck.hidden_face_image_path = null;
				this.deck.active = 0;
			},
			deleteDeck: function(deck) {
				axios.delete('/api/decks/'+ deck.id)
				.then(response => {
					console.log('Deck Deleted');
				}).then(response => {
					this.getDecks();
				});
				
			},
			cancelEdit: function () {
				this.isCreating = false;
				this.isEdit = false;
				this.name = null;
				this.hidden_face_image_path = null;
				this.active = 0;
			},
			getDecks: function() {
                axios.get('/api/decks')
                    .then(response=>{
                        this.decks = response.data;
                        console.log(this.decks);
                    });
			},

			pieceImageURL (path) {
                var imgSrc = String(path);
                return 'img/' + imgSrc + '.png';
            },
			
		},
		mounted() {
			this.getDecks();
		}
	}
</script>

<style scoped>

</style>
