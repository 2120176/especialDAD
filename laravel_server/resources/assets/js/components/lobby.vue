<template>
	<table class="table table-striped">
	    <thead>
	        <tr>
	            <th>ID</th>
	            <th>Player 1</th>
	            <th>Actions</th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr v-for="game in games"  :key="game.gameID">
	            <td>{{ game.gameID }}</td>
	            <td>{{ game.player1 }}</td>
	            <td v-if="gameIsFull(game)">
	                <a class="btn btn-xs btn-danger">Game is Full</a>
	            </td>
							<td v-else>
								<a class="btn btn-xs btn-danger" v-on:click.prevent="join(game)">Join</a>
							</td>
	        </tr>
	    </tbody>
	</table>
</template>

<script type="text/javascript">
	// Component code (not registered)
	module.exports={
		props: ['games'],
        methods: {
            join:function(game) {
            	this.$emit('join-click', game);
            },
						gameIsFull(game) {
							return game.numPlayers >= 4;
						}
        },
	}
</script>

<style scoped>

</style>
