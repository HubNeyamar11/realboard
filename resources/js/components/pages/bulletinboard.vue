<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">댓글 테스트 &nbsp; <Button @click="addModal=true" ><Icon type="md-add"/>추가</Button></p>

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
							<tr v-for="(tag, i) in tags " :key="i" v-if="tags.length" >
								<td>{{tag.id}}</td>
								<td class="_table_name">{{tag.tagName}}</td>
								<td>{{tag.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(tag,i)" >수정</Button>
									<Button type="error" size="small" @click="showDeletingModal(tag, i)" :loading="tag.isDeleting"  >삭제</Button>
								</td>
							</tr>
										
						</table>
					</div>
				</div>
				  

             <!-- tag adding modal -->
				<Modal
					v-model="addModal"
					title="댓글 추가"
					:mask-closable="false"
					:closable="false"

					>
					<Input v-model="data.tagName" placeholder="댓글을 입력하세요"  />

					<div slot="footer">
						<Button type="default" @click="addModal=false">닫기</Button>
						<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : '추가'}}</Button>
					</div>

				</Modal>

				<!-- tag editing modal -->
				<Modal
					v-model="editModal"
					title="댓글 수정"
					:mask-closable="false"
					:closable="false"

					>
					<Input v-model="editData.tagName" placeholder="수정할 댓글 입력"  />

					<div slot="footer">
						<Button type="default" @click="editModal=false">닫기</Button>
						<Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : '수정'}}</Button>
					</div>

				</Modal>

				
				<!-- delete alert modal -->
				<Modal v-model="showDeleteModal" width="360">
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
				</Modal>
				

			</div>
		</div>
    </div>
</template>

<script>
export default {
	data(){
		return {
			data : {
				tagName: ''
			}, 
			addModal : false, 
			editModal : false, 
			isAdding : false, 
			tags : [],
			editData : {
				tagName: ''
			},
			index : -1,
			showDeleteModal: false,
			isDeleting : false,
			deleteItem: {},
			deletingIndex: -1 
					
		}
	},
	methods : {
		async addTag(){
			if(this.data.tagName.trim()=='') return this.e('Tag name is required')
														//common.js의 method 에러의 e
			const res = await this.callApi('post', 'app/create_tag', this.data)
											//web.php의 post방식
			if(res.status===201){
				this.tags.unshift(res.data)
				this.s('댓글이 성공적으로 추가!')
				this.addModal = false
				this.data.tagName = ''
			}else{
					if(res.status==422){
						if(res.data.errors.tagName){
							this.e(res.data.errors.tagName[0])
						}
					}else{
						this.swr()
					}
					
				}
				
			
		},
		async editTag(){
			if(this.editData.tagName.trim()=='') return this.e('댓글이 필요합니다')
														//common.js의 method 에러의 e
			const res = await this.callApi('post', 'app/edit_tag', this.editData)
											//web.php의 post방식
			if(res.status===200){
				this.tags[this.index].tagName = this.editData.tagName
				this.s('댓글이 성공적으로 수정!')
				this.editModal = false
				
			}else{
				if(res.status==422){
					if(res.data.errors.tagName){
						this.e(res.data.errors.tagName[0])
					}
					
				}else{
					this.swr()
				}
				
			}
		},
		
		showEditModal(tag, index){
			let obj={
				id : tag.id,
				tagName : tag.tagName
			}
			this.editData = obj
			this.editModal = true
			this.index = index
		},	
		async deleteTag(){
			this.isDeleting = true
			const res = await this.callApi('post','app/delete_tag', this.deleteItem)
			if(res.status===200){
				this.tags.splice(this.deletingIndex,1)
				this.s('댓글이 성공적으로 삭제!')
			}else{
				this.swr()
			}
			this.isDeleting = false
				this.showDeleteModal = false
		},
		showDeletingModal(tag, i){
			this.deleteItem = tag
			this.deletingIndex = i
			this.showDeleteModal = true
		}
	}, 
	
		
	
	async created(){
		const res = await this.callApi('get', 'app/get_tags');
		
		if(res.status==200){
			
			this.tags = res.data
		}else{
			this.swr()
		}
	}
	
	
	
	
	
}
</script>