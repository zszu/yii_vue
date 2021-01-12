<template>
    <div>
        <!-- 图片上传组件辅助-->
        <el-upload
                class="avatar-uploader"
                :action="serverUrl"
                name="file"
                :headers="header"
                :show-file-list="false"
                list-type="picture"
                :multiple="false"
                :on-success="uploadSuccess"
                :on-error="uploadError"
                :before-upload="beforeUpload">
        </el-upload>
    </div>
</template>

<script>
    export default {

        data(){
            return {
                quillUpdateImg: false, // 根据图片上传状态来确定是否显示loading动画，刚开始是false,不显示
                // 上传的图片服务器地址
                serverUrl : 'http://yii.vue.test/v1/common/images',
                header: {
                    // token: sessionStorage.token
                } // 有的图片服务器要求请求头需要有token
            };
        },
        methods:{
            uploadSuccess(res, file){
                this.$emit('uploadSuccess',res, file);
            },
            uploadError(){
                // loading动画消失
                this.quillUpdateImg = false;
                this.$message.error('图片插入失败');
            },
            beforeUpload(){
                // 显示loading动画
                this.quillUpdateImg = true;
            },
        }
    }
</script>

<style scoped>

</style>