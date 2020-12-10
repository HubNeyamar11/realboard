<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">게시판 &nbsp; <Button @click="addModal=true" ><Icon type="md-add"/>추가</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>제목</th>
								<th>날짜</th>
								<th>수정 및 삭제</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(board, i) in boardLists " :key="i" v-if="boardLists.length" >
								<td>{{board.id}}</td>
								<td class="_table_name"  @click="showTagModal(board,i)">{{board.title}}</td>
								<td>{{board.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(board,i)" >수정</Button>
									<Button type="error" size="small" @click="showDeletingModal(board, i)" :loading="board.isDeleting"  >삭제</Button>
								</td>
							</tr>
										
						</table>
					</div>
				</div>
				  

             <!-- 추가 모달창 -->
				<Modal
					v-model="addModal"
					title="카테고리 추가"
					:mask-closable="false"
					:closable="false"

					>
                   
					<Input v-model="data.title" placeholder="제목을 입력하세요"  />
                     <Input v-model="data.content" maxlength="100" show-word-limit type="textarea" placeholder="내용을 입력하세요."  />
                    <div class="space"></div>
                     <Upload
                        ref="uploads"
                        type="drag"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With': 'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :on-error="handleError"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        action="/app/upload"
                    >
                     <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>Click or drag files here to upload</p>
                    </div>
                    </Upload>

                   <div class="demo-upload-list" v-if="data.contentImage">
                    <img :src="`${data.contentImage}`" />
                    <div class="demo-upload-list-cover">
                    <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                    </div>
                </div>

					<div slot="footer">
						<Button type="default" @click="addModal=false">닫기</Button>
						<Button type="primary" @click="addBoard" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : '추가'}}</Button>
					</div>

				</Modal>

				<!-- 수정 모달창 -->
				<Modal
					v-model="editModal"
					title="게시물 수정"
					:mask-closable="false"
					:closable="false"

					>
					<Input v-model="editData.title" placeholder="제목을 입력하세요"  />
                     <Input v-model="editData.content" maxlength="100" show-word-limit type="textarea" placeholder="내용을 입력하세요."  />
                    <div class="space"></div>
                     <Upload v-show="iscontentImageNew"
                        ref="editDataUploads"
                        type="drag"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With': 'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :on-error="handleError"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        action="/app/upload"
                    >
                     <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>Click or drag files here to upload</p>
                    </div>
                    </Upload>

                   <div class="demo-upload-list" v-if="editData.contentImage">
                    <img :src="`${editData.contentImage}`" />
                    <div class="demo-upload-list-cover">
                    <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
                    </div>
                </div>

					<div slot="footer">
						<Button type="default" @click="closeEditModal">닫기</Button>
						<Button type="primary" @click="editBoard" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : '수정'}}</Button>
					</div>

				</Modal>

				
				<!-- 삭제 모달창 -->
				<!-- <Modal v-model="showDeleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>삭제</span>
					</p>
					<div style="text-align:center">
						<p>정말로 삭제 하실래요?</p>
						
					</div>
					<div slot="footer">
						<Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteTag" >삭제</Button>
					</div>
				</Modal> -->
				<deleteModal></deleteModal>

                <!-- 클릭 모달창 -->
				<Modal
					v-model="showModal"
					title="카테고리 내용"
					:mask-closable="false"
					:closable="false"

					>
					<h1>{{showData.title}}</h1>
                    <p>{{showData.content}}</p>
                   
                    <img :src="showData.contentImage"/>
                    
                  	<div slot="footer">
						<Button type="default" @click="showModal=false">닫기</Button>
						
					</div>

				</Modal>
				

			</div>
		</div>
    </div>
</template>

<script>
import deleteModal from "../components/deleteModal.vue";
import { mapGetters } from "vuex";
export default {
  
	data(){
		return {
			data : {
                title: '',
                content: '',
                contentImage: ''
			}, 
			addModal : false, 
			editModal : false, 
            isAdding : false, 
            showModal : false,
            boardLists : [],
            showData:{
                title: '',
                content: '',
                contentImage: ''
            },
			editData : {
				title: '',
                content: '',
                contentImage: ''
			},
			index : -1,
			showDeleteModal: false,
			isDeleting : false,
			deleteItem: {},
            deletingIndex: -1,
            token:'',
            iscontentImageNew: false,
            isEditingItem: false, 
					
		}
    },
    
	methods : {
		async addBoard(){
			if(this.data.title.trim()=='') return this.e('제목이 필요')
                                                        //common.js의 method 에러의 e
            if(this.data.content.trim()=='') return this.e('내용이 필요')
                                                        //common.js의 method 에러의 e
            if(this.data.contentImage.trim()=='') return this.e('아이콘 이미지가 필요')
                                                        //common.js의 method 에러의 e
            this.data.contentImage = `${this.data.contentImage}`
			const res = await this.callApi('post', 'app/create_board', this.data)
											// post방식
			if(res.status===201){
				this.boardLists.unshift(res.data)
				this.s('게시글이 성공적으로 추가!')
				this.addModal = false
                this.data.title = ''
                this.data.content = ''
                this.data.contentImage=''
			}else{
					if(res.status==422){
						if(res.data.errors.title){
							this.e(res.data.errors.title[0])
                        }
                        if(res.data.errors.content){
							this.e(res.data.errors.content[0])
                        }
                        if(res.data.errors.contentImage){
							this.e(res.data.errors.contentImage[0])
						}
					}else{
						this.swr()
					}
					
				}
				
			
		},
		async editBoard(){
			if(this.editData.title.trim()=='') return this.e('제목이 필요')
                                                        //common.js의 method 에러의 e
            if(this.editData.content.trim()=='') return this.e('내용이 필요')
                                                        //common.js의 method 에러의 e                                            
            if(this.editData.contentImage.trim()=='') return this.e('아이콘 이미지가 필요')
                                                        //common.js의 method 에러의 e
			const res = await this.callApi('post', 'app/edit_board', this.editData)
											// post방식
			if(res.status===200){
				this.boardLists[this.index].title = this.editData.title
				this.s('카테고리가 성공적으로 수정!')
				this.editModal = false
				
			}else{
				if(res.status==422){
					if(res.data.errors.title){
							this.e(res.data.errors.title[0])
                        }
                        if(res.data.errors.content){
							this.e(res.data.errors.content[0])
                        }
                        if(res.data.errors.contentImage){
							this.e(res.data.errors.contentImage[0])
						}
				}else{
					this.swr()
				}
				
			}
        },
        		
		showEditModal(board, index){
			// let obj={
			// 	id : category.id,
			// 	categoryName : category.categoryName
			// }
			this.editData = board
			this.editModal = true
            this.index = index
            this.isEditingItem = true
		},	
		
		showDeletingModal(board, i){
			const deleteModalObj = {
				showDeleteModal: true,
				deleteUrl: "app/delete_board",
				data: board,
				deletingIndex: i,
				isDeleted: false
			};
			this.$store.commit("setDeletingModalObj", deleteModalObj);
        },
        handleSuccess(res, file) {
            res = `/uploads/${res}`
            if(this.isEditingItem){
                return this.editData.contentImage = res 
            }
           this.data.contentImage = res
        },
        handleError(res, file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: `${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            });
        },
        
        handleFormatError (file){
            this.$Notice.warning({
                title: 'The file firmat is incorrect',
                desc: 'File frmat of' + file.name + ' is incorrect, please selct jpg or png'
            });
        },
        handleMaxsize(file){
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File frmat of' + file.name + ' is Too large, no more than 2M'
            });
        },
        async deleteImage(isAdd=true){
            let image;
            if(!isAdd){ //수정하기 위한 삭제
                this.iscontentImageNew = true;
                image = this.editData.contentImage;
                this.editData.contentImage = '';
                this.$refs.editDataUploads.clearFiles();
            }else{ //삭제
                image = this.data.contentImage;
                this.data.contentImage = '';
                this.$refs.uploads.clearFiles();
            }
            
            const res = await this.callApi('post', 'app/delete_image',{imageName: image})
            if(res.status!=200){
                this.data.contentImage = image
                this.swr()
            }
        },
        closeEditModal(){
            this.isEditingItem = false
            this.editModal = false
        },
        showTagModal(board, index){
            let obj={
				id : board.id,
                title : board.title,
                content: board.content,
                contentImage : board.contentImage
			}
			this.showData = obj
			this.showModal = true
            this.index = index
        }
    },
		
	//get 방식 리드
	async created(){
        this.token= window.Laravel.csrfToken
		const res = await this.callApi('get', 'app/get_board');
		
		if(res.status==200){
			
			this.boardLists = res.data
		}else{
			this.swr()
		}
	},

	components: {
			deleteModal
	},
	computed: {
			...mapGetters(["getDeleteModalObj"])
	},
	watch: {
			getDeleteModalObj(obj) {
			if (obj.isDeleted) {
				this.boardLists.splice(obj.deletingIndex, 1);
			}
			}
	}
	
	
	
	
	
}
</script>