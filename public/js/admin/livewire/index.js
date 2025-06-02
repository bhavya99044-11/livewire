    document.addEventListener("livewire:initialized", () => {
        const initAction = () => {
            let selectBoxes = document.querySelectorAll('.selectAdmin')
            selectBoxes.forEach((item) => {
                item.checked = false
            })
            const allSelect = document.getElementById('allSelect')
            allSelect.checked = false;
            const adminAction = document.getElementById('adminActions')
            console.log(adminAction)
            allSelect.addEventListener('change', function(item) {
                let selectBoxes = document.querySelectorAll('.selectAdmin')
                selectBoxes.forEach((item) => {
                    item.checked = allSelect.checked
                })
                adminAction.value = ''
            })

            adminAction.addEventListener('change', async function(item) {
                let selectBoxes = document.querySelectorAll('.selectAdmin')
                const action = item.target.value
                const selectCheck = Array.from(selectBoxes).filter(item => item.checked).map(
                    item => item.value)
                if (selectCheck.length > 0) {
                    await swalConfirmation(action, 'are you sure?', selectCheck)
                    selectBoxes.forEach((item) => {
                        item.checked = false
                    })
                    allSelect.checked = false;
                    adminAction.value = ''
                }
            })
            $('#allSelect').on('change',function(){
                if(this.checked)
                    $('#adminActions').prop('disabled',false)
                else
                $('#adminActions').prop('disabled',true)
                
            })

            $('.selectAdmin').on('change',function(){
               const selectedAdmins=Array.from(document.querySelectorAll('.selectAdmin')).filter((item)=>item.checked==true)
               if(selectedAdmins.length<=0)
                $('#adminActions').prop('disabled',true)
               else
               $('#adminActions').prop('disabled',false)
            })
            
        }
        initAction();
                // Re-run after any Livewire DOM update
        Livewire.hook('commit', () => {
            initAction();
        });

    })
