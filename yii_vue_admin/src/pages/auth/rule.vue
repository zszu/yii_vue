<template>
    <div class="table">
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-cascades"></i> 菜单权限管理</el-breadcrumb-item>
                <el-breadcrumb-item>角色列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="container">

            <div>
                <el-button icon="el-icon-add" @click="handleAdd"  type="success">添加</el-button>
            </div>

            <el-table
                    :data="tableData"
                    border class="table"
                    ref="multipleTable"
                    row-key="id"
                    :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
                    @selection-change="handleSelectionChange">

                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="id" label="序号" sortable width="150"></el-table-column>
                <el-table-column prop="title" label="名称" width="160"  :formatter="formattitle">
                </el-table-column>
                <el-table-column prop="name" label="别名" >
                </el-table-column>

                <el-table-column label="操作" width="240" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" icon="el-icon-edit" @click="addSub(scope.$index, scope.row)">添加子权限</el-button>
                        <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                        <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <!-- 编辑弹出框 -->
        <el-dialog :title="type ? '添加' : '编辑' " :visible.sync="editVisible" width="30%">
            <el-form ref="form" :model="form" label-width="100px">
                <el-form-item label="权限名称">
                    <el-input v-model="form.title"></el-input>
                </el-form-item>
                <el-form-item label="权限路由">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="editVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveEdit">确 定</el-button>
            </span>
        </el-dialog>

        <!-- 删除提示框 -->
        <el-dialog title="提示" :visible.sync="delVisible" width="300px" center>
            <div class="del-dialog-cnt">删除不可恢复，是否确定删除？</div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="delVisible = false">取 消</el-button>
                <el-button type="primary" @click="deleteRow">确 定</el-button>
            </span>
        </el-dialog>


    </div>
</template>

<script>
    export default {
        name: 'rule',
        components:{},
        data() {
            return {
                tableData: [],
                cur_page: 1,
                multipleSelection: [],
                editVisible: false,
                delVisible: false,
                showIcon:false,
                form: {
                    title: '',
                    name:'',
                    id:0,
                },
                type: 1,
                id:0,
                treeData:[],
                checkedValue:[],
                defaultProps: {
                    children: 'children',
                    label: 'name'
                },
                index:0,
            }
        },
        created() {
            this.getData();
        },
        activated() {
            this.form.pid = 0;
            this.getData();
        },

        methods: {

            formattitle(row, column) {
                if(row.level<1 || row.pid<=0) return row.title;
                let blank = "\xa0\xa0".repeat(row.level);
                let pre = blank+'|';
                let line = '--';
                let line2 = line.repeat(row.level);
                return pre+line2+row.title;
            },

            // 分页导航
            handleCurrentChange(val) {
                this.cur_page = val;
                this.getData();
            },
            // 获取数据
            getData() {
                this.$http_.get('auth/rule/index',{pid:this.form.pid},(res)=>{
                    // console.log(res.data);
                    this.tableData = res.data;
                });
            },
            //添加
            handleAdd(){
                this.form.title = '';
                this.form.name = '';
                this.type = 1;
                this.id = 0;
                this.editVisible = true;
            },
            //添加子权限
            addSub(index,row){
                this.form.pid = row.id;
                this.id = 0;
                this.form.level = row.level + 1;
                console.log(this.level);

                this.handleAdd();
            },
            //修改
            handleEdit(index, row) {
                this.type = 0;
                this.id = row.id;
                const item = this.tableData[index];
                this.form = {
                    title: item.title,
                    name: item.name,
                    id:this.id,
                };
                this.editVisible = true;
            },
            handleDelete(index, row) {
                this.id = row.id;
                this.type = index;
                this.index = index;
                this.delVisible = true;
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },

            // 保存编辑
            saveEdit() {
                var url = this.id ? 'auth/rule/update' : 'auth/rule/create';
                // console.log(url);
                // return;

                this.$http_.post(url,this.form,(res)=>{
                    if(res.code=='200'){
                        this.$message.success(res.message);
                        this.getData();
                    }else{
                        this.$message.warning(res.message);
                    }
                });
                this.editVisible = false;
            },
            // 确定删除
            deleteRow(){
                this.$http_.post('auth/rule/delete',{id:this.id},(res)=>{
                    if(res.code=='200'){
                        this.$message.success(res.message);
                        this.tableData.splice(this.index, 1);

                    }else{
                        this.$message.warning(res.message);
                    }
                });
                this.delVisible = false;
            },

        }
    }

</script>

<style scoped>
    .iconfont{
        font-size: 20px;
        /*font-weight: bold;*/
    }
    .handle-box {
        margin-bottom: 20px;
    }

    .handle-select {
        width: 120px;
    }

    .handle-input {
        width: 300px;
        display: inline-block;
    }
    .del-dialog-cnt{
        font-size: 16px;
        text-align: center
    }
    .table{
        width: 100%;
        font-size: 14px;
    }
    .red{
        color: #ff0000;
    }
</style>
