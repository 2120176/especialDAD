<template>
<div>
    <div v-if="isReason" class="form-group" >
        <label for="inputReason">Block Reason - {{ user.nickname }}</label>
        <input required
            type="text" class="form-control" v-model="blockReason"
            name="reason" id="reason"/>
        <a class="btn btn-xs btn-warning" v-on:click.prevent="blockUser(user)">Block User</a>
        <a class="btn btn-xs btn-default" v-on:click.prevent="cancel">Cancel</a>
    </div>

    <div v-if="isReasonUnlock" class="form-group" >
        <label for="inputUnlockReason">Unlock Reason - {{ user.nickname }}</label>
        <input required
            type="text" class="form-control" v-model="unblockReason"
            name="unlockReason" id="unlockReason"/>
        <a class="btn btn-xs btn-success" v-on:click.prevent="unlockUser(user)">Unlock User</a>
        <a class="btn btn-xs btn-default" v-on:click.prevent="cancel">Cancel</a>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th><strong>Name</strong></th>
            <th><strong>Email</strong></th>
            <th><strong>Nickname</strong></th>
            <th><strong>Actions</strong></th>
        </tr>
        </thead>
        <tbody>
        
        <tr v-for="user in users"  :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.nickname }}</td>
            <td v-if="user.admin !== 1">
                <a v-if="user.blocked == 0" class="btn btn-xs btn-warning" v-on:click.prevent="setReasonVisible(user)">Block User</a>
                <a v-if="user.blocked == 1" class="btn btn-xs btn-success" v-on:click.prevent="setReasonUnlock(user)">Unlock User</a>
                <a class="btn btn-xs btn-danger" v-on:click.prevent="deleteUser(user)">Delete User</a>
                <a class="btn btn-xs btn-primary" v-on:click.prevent="userDetails(user)">Details</a>
            </td>
        </tr>
        </tbody>
        
    </table>
</div>
</template>

<script type="text/javascript">
    import UserDetails from './adminUserDetails.vue';

    export default {
        data: function(){
            return {
                users: {},
                currentPassword: null,
                changePasswordAdmin: false,
                user: [],
                details : false,
                isReason : false,
                blockReason : null,
                isReasonUnlock : false,
                unblockReason : null,
            }
        },
        
        methods: {
            home: function () {
            this.$router.push('/adminMasterPage');
        },
            getUsers: function(){
                axios.get('/api/users')
                    .then(response=>{
                        this.users = response.data.data;
                        console.log(this.users);
                    });
            },
            blockUser: function(user){
                
                axios.put('/api/user/blocked/'+ user.id, { 'reason_blocked' : this.blockReason })
	                .then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
                        Object.assign(this.user, response.data.data);   
                        this.isReason = false;
                        this.blockReason = null;
                        //this.$emit('block-click', user);
                    });
            },

            unlockUser: function (user) {
                axios.put('/api/user/unblocked/'+ user.id, { 'reason_reactivated' : this.unblockReason })
	                .then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
                        Object.assign(this.user, response.data.data);   
                        this.isReasonUnlock = false;
                        this.unblockReason = null;
                        //this.$emit('block-click', user);
                    });
                this.$emit('unlock-click', user);
            },

            deleteUser: function(user){
                if (window.confirm("Do you really want to delete the user " + user.name + "?")) {
                    axios.delete('api/users/' + user.id)
                        .then(response => {
                            this.successMessage = 'User Deleted';
                        }).then(response => {
                        this.getUsers();
                    });
                }
            },
            changePassword: function () {
                this.changePasswordAdmin = true;
            },
            clear: function () {
                this.changePasswordAdmin = false;
            },
            userDetails: function (user) {
              axios.get('/api/users/' + user.id)
              .then(response => {
                console.log("userDetails UsersList");
                this.user = response.data.data;
                console.log(this.user.id);
                
              }).then(response => {
                  this.$router.push('adminUserDetails/' + this.user.id);
              });
            },
            setReasonUnlock: function (user) {
                this.isReasonUnlock = true;
                this.user = user;
            },
            setReasonVisible: function (user) {
                this.isReason = true;
                this.user = user;
            },
            cancel: function () {
                this.isReason = false;
            }
        },
        components: {
          'user-details' : UserDetails,
        },
        mounted () {
            this.getUsers();
        }
    }
</script>
