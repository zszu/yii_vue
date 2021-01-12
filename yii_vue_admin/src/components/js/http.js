import axios from 'axios'
var baseUrl = 'http://yii.vue.test/v1/';

const http = {
    post(url,data,callback){

        data.access_token = localStorage.getItem('access_token') ? localStorage.getItem('access_token') : '';

        var url = data.id ? baseUrl+url + '?id='+ data.id : baseUrl+url;
        console.log(url);

        axios.post(url , data)
            .then((response)=>{
                callback(response.data)
            })
            .catch(function (error) {
                console.log(error);
            });
    },
    get(url,data,callback){

        var qs = require("querystring");


        data = {
            params : data
        };
        var url = data.id ? baseUrl+url + '?id='+ data.id : baseUrl+url;
        console.log(url);
        axios.get(url , data)
            .then((response)=>{
                callback(response.data)
            })
            .catch(function (error) {
                console.log(error);
            });
    },
    put(url , data , callback){
        data.access_token = localStorage.getItem('access_token') ? localStorage.getItem('access_token') : '';

        var url = data.id ? baseUrl+url + '?id='+ data.id : baseUrl+url;

        axios.put(url , data)
            .then((response)=>{
                callback(response.data)
            })
            .catch(function (error) {
                console.log(error);
            });
    },
    delete(url , data , callback){
        data.access_token = localStorage.getItem('access_token') ? localStorage.getItem('access_token') : '';

        var url = data.id ? baseUrl+url + '?id='+ data.id : baseUrl+url;

        axios.delete(url , data)
            .then((response)=>{
                callback(response.data)
            })
            .catch(function (error) {
                console.log(error);
            });
    },
};

export default http;