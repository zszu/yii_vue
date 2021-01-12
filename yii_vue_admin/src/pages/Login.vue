<template>
    <div class="login-wrap">
            <div class="ms-login">
                <div class="ms-title">后台管理系统</div>
                <el-form :model="ruleForm"  ref="ruleForm" label-width="0px" class="ms-content">
                    <el-form-item prop="username">
                        <el-input v-model="ruleForm.username" placeholder="username">
                            <el-button slot="prepend" icon="el-icon-lx-people"></el-button>
                        </el-input>
                    </el-form-item>
                    <el-form-item prop="password">
                        <el-input type="password" placeholder="password" v-model="ruleForm.password" @keyup.enter.native="submitForm('ruleForm')">
                            <el-button slot="prepend" icon="el-icon-lx-lock"></el-button>
                        </el-input>
                    </el-form-item>
                    <div class="login-btn">
                        <el-button type="primary" @click="submitForm('ruleForm')">登录</el-button>
                    </div>
                    <!-- <p class="login-tips">Tips : 用户名和密码随便填。</p> -->
                </el-form>
            </div>
        </div>
</template>

<script>
    import store from '../store'
    export default {
        name: "Login",
        data () {
            return {
                msg: 'login',
                ruleForm: {
                    username: 'admin',
                    password: '123456'
                },
            }
        },
        methods:{
            submitForm(formName){
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        var data = this.ruleForm;
                        this.$http_.post('site/login' , data , (res)=>{
                            // console.log(res);
                            //登录成功
                            if(res.code=='200'){
                                //设置左侧 菜单
                                window.sessionStorage.clear();
                                window.localStorage.setItem('access_token',res.data.userInfo.access_token);
                                window.localStorage.setItem('userInfo',JSON.stringify(res.data.userInfo));

                                this.set_url(res.data);
                                this.$router.push('/dashboard');
                            }else {
                                // console.log(JSON.stringify(res.message));
                                alert(JSON.stringify(res.message));
                            }
                        });
                    } else {
                        // console.log('error submit!!');
                        return false;
                    }
                });
            },
            //设置路由 GenerateRoutes
            set_url(data){
                let menus = data.menus;
                store.dispatch('GenerateRoutes',menus).then(() => {
                    this.$router.addRoutes(store.getters.addRouters);
                    //所有 菜单
                    localStorage.setItem('menus', JSON.stringify(menus));
                    // this.$router.push('/dashboard');

                });
                //树状 菜单
                window.localStorage.setItem('menus_tree',JSON.stringify(data.menus_tree));

            }
        }
    }
</script>

<style scoped>
    .login-wrap{
        position: relative;
        width:100%;
        height:100%;
        /*background-image: url(../assets/login-bg.jpg);*/
        background-size: 100%;
    }
    .ms-title{
        width:100%;
        line-height: 50px;
        text-align: center;
        font-size:20px;
        color: #fff;
        border-bottom: 1px solid #ddd;
    }
    .ms-login{
        position: absolute;
        left:50%;
        top:50%;
        width:350px;
        margin:-190px 0 0 -175px;
        border-radius: 5px;
        background: rgba(255,255,255, 0.3);
        overflow: hidden;
    }
    .ms-content{
        padding: 30px 30px;
    }
    .login-btn{
        text-align: center;
    }
    .login-btn button{
        width:100%;
        height:36px;
        margin-bottom: 10px;
    }
    .login-tips{
        font-size:12px;
        line-height:30px;
        color:#fff;
    }
</style>