<template>
   <div>
       <el-row :gutter="20">
           <el-col :span="24">
               <el-card shadow="hover" class="mgb20" style="height:252px;">
                   <div class="user-info">
                       <div class="avatar">
                           <span><croperUpload  @save="saveAvatar" :size="100" :opacity="0"></croperUpload></span>
                           <img :src="user.avatar?user.avatar:'/static/img/img.jpg'" class="user-avator" alt="">

                       </div>

                       <div class="user-info-cont">
                           <div class="user-info-name">{{user.username}}</div>
                           <div>{{user.role}}</div>
                           <el-button type="success" icon="el-icon-edit" size="mini" @click="showEdit">密码修改</el-button>
                       </div>
                   </div>
                   <div class="user-info-list">上次登录时间：{{user.lastLoginAt*1000 | formatDate}}</div>
               </el-card>

           </el-col>
       </el-row>

       <!-- 编辑弹出框 -->
       <el-dialog :title="'密码修改'" :visible.sync="editVisible" width="30%">
           <el-form ref="form" :model="user" label-width="100px">
               <el-form-item label="旧密码">
                   <el-input type="password" v-model="user.oldPassword"></el-input>
               </el-form-item>
               <el-form-item label="新密码">
                   <el-input type="password" v-model="user.password"></el-input>
               </el-form-item>
           </el-form>
           <span slot="footer" class="dialog-footer">
                <el-button @click="editVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveEdit">确 定</el-button>
            </span>
       </el-dialog>
   </div>
</template>

<script>
    import croperUpload from '@/components/untils/croper-upload';

    export default {
        name: "dashboard",
        data () {
            return {
                editVisible:false,
                user:{
                    username : 'admin',
                    role : '超级管理员',
                    password:'',
                    oldPassword:'',
                    avatar:'',
                    lastLoginAt:'',
                },
            }
        },
        components:{croperUpload},
        created(){
            this.$nextTick(()=>{
                this.get_data();
            })
        },
        methods:{
            get_data() {

                let userInfo = window.localStorage.getItem('userInfo');
                userInfo = JSON.parse(userInfo);

                this.user.lastLoginAt = userInfo.last_login_at;
                this.user.username = userInfo.username;
                this.user.oldPassword = userInfo.password;
                this.user.id = userInfo.id;
                this.user.avatar = userInfo.avatar;

            },
            showEdit(){
                //控制弹框 显示
                this.editVisible = true;
                this.user.password = '';
            },
            saveEdit(){
                this.$http_.post('user/reset-pwd',this.user,(res)=>{
                    if(res.code== 200){
                        this.$message.success(res.message);
                    }else{
                        this.$message.warning(JSON.stringify(res.message));
                    }
                    this.editVisible= false;
                })
            },
            //裁剪图片
            saveAvatar(imgUrl){
                this.$http_.post('user/update-avatar',{avatar:imgUrl,id:this.user.id},(res)=>{
                    this.ifload = false;
                    if(res.code == 200){
                        this.user.avatar = res.data.avatar;

                        this.$message.success('修改成功');
                    }else{
                        console.log(res);
                        this.$message.warning(res.message);
                    }
                });
            },

        },

    }
</script>



<style scoped>
    .el-row {
        margin-bottom: 20px;
    }

    .user-info {
        display: flex;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 2px solid #ccc;
        margin-bottom: 20px;
    }

    .user-avator {
        width: 120px;
        height: 120px;
        border-radius: 50%;
    }

    .user-info-cont {
        padding-left: 50px;
        flex: 1;
        font-size: 14px;
        color: #999;
    }

    .user-info-cont div:first-child {
        font-size: 30px;
        color: #222;
    }

    .user-info-list {
        font-size: 14px;
        color: #999;
        line-height: 25px;
    }

    .user-info-list span {
        margin-left: 70px;
    }

    .mgb20 {
        margin-bottom: 20px;
    }

    .avatar {
        position: relative;
    }
    .avatar span{
        position: absolute;
        top: 0px;
        left: 0px;
        display: inline-block;
        width: 100%;
    }

</style>
