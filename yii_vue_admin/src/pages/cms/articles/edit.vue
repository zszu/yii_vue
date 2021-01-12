<template>
        <div>
                <el-form ref="form" :model="form" label-width="100px">
                    <el-form-item label="标题">
                        <el-input v-model="form.title"></el-input>
                    </el-form-item>
                    <el-form-item label="作者">
                        <el-input v-model="form.publisher"></el-input>
                    </el-form-item>
                    <el-form-item label="排序">
                        <el-input v-model="form.order_by"></el-input>
                    </el-form-item>
                    <el-form-item label="类别">
                        <el-input v-model="form.type"></el-input>
                    </el-form-item>
                    <el-form-item label="内容">
                        <editor v-model="form.content"></editor>
                    </el-form-item>
                    <el-form-item label="状态">
                        <el-input v-model="form.status"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button @click="editVisible = false">取 消</el-button>
                        <el-button type="primary" @click="saveEdit">确 定</el-button>
                    </el-form-item>
                </el-form>

        </div>
</template>

<script>
    import editor from '@/components/untils/qui_editor';

    export default {
        name: "articles-edit",
        components:{editor},
        data(){
            return {
                editVisible:false,
                form:{
                    title:'test',
                    publisher:'admin',
                    order_by: 1,
                    type: 1,
                    content:'',
                    status: 1,
                }
            }
        },
        created(){

        },
        activated(){

        },
        methods:{
            saveEdit(){
                console.log(this.form);
                var url = this.id ? 'cms/articles/update' : 'cms/articles/create';

                this.$http_.post(url,this.form,(res)=>{
                    console.log(res.data);
                    return;

                    if(res.code=='200'){
                        this.$message.success(res.message);
                    }else{
                        this.$message.warning(res.message);
                    }
                });
            }
        },
    }
</script>

<style scoped>

</style>