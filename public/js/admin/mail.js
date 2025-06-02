    document.addEventListener('DOMContentLoaded',function(){
        const allSelect=document.getElementById('allSelect')
        const selectBoxes=document.querySelectorAll('.selectAdmin')
        allSelect.addEventListener('change',function(){
            
            selectBoxes.forEach((item)=>{
                item.checked=allSelect.checked
            })
        })

        const adminAction=document.getElementById('adminActions')
        adminActions.addEventListener('change',async function(item){
            const action=item.target.value
            const selectCheck=Array.from(selectBoxes).filter(item=>item.checked).map(item=>item.value)
        if(selectCheck.length >0){
            await swalConfirmation(action,'are you sure?',selectCheck)
            console.log(1)
            selectBoxes.forEach((item)=>{
                item.checked=false
            })
            adminAction.value=''
        }
           
        })
    })
