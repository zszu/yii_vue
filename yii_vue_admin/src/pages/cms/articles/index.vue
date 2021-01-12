<template>
    <div class="table">
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-cascades"></i> 文章管理</el-breadcrumb-item>
                <el-breadcrumb-item>文章列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="container">

            <div>
                <el-button icon="el-icon-add" @click="handleAdd"  type="success">添加</el-button>
            </div>

            <el-table :data="tableData" border class="table" ref="multipleTable" @selection-change="handleSelectionChange">

                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="id" label="序号" sortable width="150"></el-table-column>
                <el-table-column prop="title" label="名称" width="160" >
                </el-table-column>
                <el-table-column prop="summary" label="描述">
                </el-table-column>

                <el-table-column label="操作" width="320" align="center">
                    <template slot-scope="scope">
                      <route-link :to="'/pages/cms/articles/edit'+scope.$index">
                        <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                      </route-link>
                        <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
          <br>
            <div class="block">
                <el-pagination
                        v-show="pageSize>0"
                        background
                        layout="total ,sizes, prev, pager, next , jumper"
                        @current-change="handleCurrentChange"
                        :page-size="pageSize"
                        :total="totalPage">
                </el-pagination>
            </div>
        </div>


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
        name: 'articles-index',
        components:{},
        data() {
            return {
                tableData: [],
                cur_page: 1,
                //每页条数
                pageSize:10,
                //所有数据
                totalPage: 1000,
                multipleSelection: [],
                editVisible: false,
                delVisible: false,
                showIcon:false,
                id:0,
                treeData:[],
                checkedValue:[],
                defaultProps: {
                    children: 'children',
                    label: 'title'
                },
                showAuth:false,
                curRoleId:0,
                index:0,
            }
        },
        created() {
            this.getData();
        },
        activated() {
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

            handleSizeChange(val) {
                console.log(`每页 ${val} 条`);
            },
            // 分页导航
            handleCurrentChange(val) {
                this.cur_page = val;
                this.getData();
            },
            // 获取数据
            getData() {
                this.totalPage = 1000;
                this.$http_.get('cms/articles/index',{'page' : this.cur_page},(res)=>{
                    console.log(res);
                    this.tableData = res.data;
                    // this.totalPage = 2;
                });
            },
            //添加
            handleAdd(){
                this.$router.push({
                    path:'/pages/cms/articles/edit' ,
                    component: resolve => require(['@/pages/cms/articles/edit.vue'], resolve),
                });
            },
            //修改
            handleEdit(index, row) {
                this.$router.push({
                    path:'/pages/cms/articles/edit' ,
                    component: resolve => require(['@/pages/cms/articles/edit.vue'], resolve),
                    params:{'id':row.id}
                });
            },
            handleDelete(index, row) {
                this.id = row.id;
                this.index = index;
                this.delVisible = true;

            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },

            // 确定删除
            deleteRow(){

                this.$http_.post('cms/articles/delete',{id:this.id},(res)=>{
                    // console.log(res);
                    if(res.code=='200'){
                        this.$message.success(res.message);

                        this.tableData.splice(this.index, 1);

                        // this.getData();
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
