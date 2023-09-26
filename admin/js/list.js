window.onload = function() {
	
    if (typeof tableList != 'undefined') {
        var container = $('#' + tableList['container']);
        
        if (typeof tableList['sort'] != 'undefined') {
            var ordering = tableList['sort'];
        } else {
            var ordering = false;
        }
        
        if (typeof tableList['search'] != 'undefined') {
            var searching = tableList['search'];
        } else {
            var searching = false;
        }
        
        if (typeof tableList['total'] != 'undefined') {
            var totalRecord = parseInt(tableList['total']);
        } else {
            var totalRecord = 0;
        }
        
        if (typeof tableList['length'] != 'undefined') {
            var pageLength = parseInt(tableList['length']);
        } else {
            var pageLength = 10;
        }
        
        if (totalRecord > pageLength) {
            var paging = true;
        } else {
            var paging = false;
        }
        
        container.DataTable({
            "stateSave": true,
            "ordering": ordering,
            "searching": searching,
            "paging": paging,
            "fnInfoCallback": function (oSettings, iStart, iEnd, iMax, iTotal, sPre) {
                        var tottext = 'entries';
                        if (iTotal > 1) { tottext = 'entries'; } else { tottext = 'entry'; }
                        if (iTotal > 0) { iStart = iStart; } else { iStart = 0; }
                        return 'Showing ' + iStart + ' to ' + iEnd + ' of ' + iTotal + ' ' + tottext;
                    },
            "pageLength": pageLength,
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                var settings = this.fnSettings();
                var str = settings.oPreviousSearch.sSearch;
                $('.td', nRow).each(function (i) {
                });
                return nRow;
            }
        });
    }
}

$(document).ready(function() {
    $('.select-all').on('click', function() {
        var table = $(this).closest('table');
        if ($(this).prop('checked')) {
            table.find('input[name="delete[]"]').prop('checked', true);
        } else {
            table.find('input[name="delete[]"]').prop('checked', false);
        }
    });
    
    $('.delete-record').on('click', function(e) {
        e.preventDefault();
        var confirmDelete = confirm("Are you sure want to delete this record?");
        if (confirmDelete == true) {
            window.location.href = $(this).attr('href');
        } else {
            return false;
        }
    });
    
    $('.delete-selected-record').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        form.addClass('asass');
        
        var selected = form.find('input[name="delete[]"]:checked').length;
        if (selected == 0) {
            alert("Please selecte  atleast one record to delete");
            return false;
        }
        
        var confirmDelete = confirm("Are you sure want to delete this selected records?");
        if (confirmDelete == true) {
            form.submit();
        } else {
            return false;
        }
    });
});

function confirmdeleteproduct(){
	var f=0;
	var len=document.userForm.length;
	for(i=1;i<len;i++){
		if(document.userForm.elements[i].checked==true){
			f=1;
			break;
		}
		else{	
			f=0;
		}
	}
	if(f==0){
		alert("Please select at least one record to delete");
		return false;
	}
	else{
		var temp=confirm("Do you really want to delete the selected records?");
			if(temp==false)	{
				return false;
			}
			else{
				document.getElementById("delId").value="del";
				document.userForm.submit();
			}
	}
}