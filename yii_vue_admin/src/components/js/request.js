import axios from 'axios'

var baseUrl = 'http://yii.vue.test/v1/';

export function post_(url,data,callback){

    data.access_token = localStorage.getItem('access_token') ? localStorage.getItem('access_token') : '';
    var qs = require("querystring");
    // data = qs.stringify(data);
    var url = data.id ? baseUrl+url + '?id='+ data.id : baseUrl+url;

    axios.post(url , data)
        .then((response)=>{
            callback(response.data)
        })
        .catch(function (error) {
            console.log(error);
        });
}


export function get_(url,data,callback){

    var qs = require("querystring");


    data = {
        params : data
    };

    axios.get(baseUrl+url,data)
        .then((response)=>{
            callback(response.data)
        })
        .catch(function (error) {
            console.log(error);
        });
}

export function delete_(url,data,callback){

    var url = data.id ? baseUrl+url + '?id='+ data.id : baseUrl+url;

    data = {
        params : data
    };

    axios.delete(url,data)
        .then((response)=>{
            callback(response.data)
        })
        .catch(function (error) {
            console.log(error);
        });
}

export function ajax_upload(url,params,act)
{
    console.log(params);
    $.ajax({
        url:baseUrl+url,
        data:params,
        type:'POST',
        dataType:'json',
        processData:false,
        contentType : false,
        // async:false,
        success:function(data){
            //$("#alert-bg").fadeOut(2000);
            act(data);
        },
        error:function(e){
            console.log('ajax error:',e);
            act(e.responseText);
        }
    });
}

