@extends('master')

@section('title', 'Vue.js App')

@section('content')
    {{--<router-link to="/users">Users</router-link> -

    <!-- <router-link to="/singletictactoe">SinglePlayer TicTacToe</router-link> -
    <router-link to="/multitictactoe">Multiplayer TicTacToe</router-link> -
    <router-link to="/blackjack">BlackJack</router-link> - -->
    <router-link to="/login">Login</router-link> -
    <router-link to="/logout">Logout</router-link> -
    <router-link to="/register">Register</router-link> -
    <router-link to="/userAccount">My Account</router-link> -
    <router-link to="/adminMasterPage">Admin Page</router-link> -
    <router-link to="/playerStatistics">Statistics</router-link> -

    <router-link to="/sueca">Sueca</router-link>

--}}

    <router-view></router-view>
@endsection

@section('pagescript')
<script src="js/vueapp.js"></script>

<!-- <script src="/js/manifest.js"></script>
<script src="/js/vendor.js"></script>
<script src="/js/vueapp.js"></script>
 -->
 @stop
