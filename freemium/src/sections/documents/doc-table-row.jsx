import { useState } from 'react';
import PropTypes from 'prop-types';

import Stack from '@mui/material/Stack';
import Button from '@mui/material/Button';
import Popover from '@mui/material/Popover';
import TableRow from '@mui/material/TableRow';
import Checkbox from '@mui/material/Checkbox';
import MenuItem from '@mui/material/MenuItem';
import InfoIcon from '@mui/icons-material/Info';
import TableCell from '@mui/material/TableCell';
import Typography from '@mui/material/Typography';

import Iconify from 'src/components/iconify';

// ----------------------------------------------------------------------

export default function UserTableRow({
  selected,
  documents,
  description,  
  date_entered,  
  doc_id,
  handleClick,
  handleViewClick,
}) {
  const [open, setOpen] = useState(null);

  
  const handleCloseMenu = () => {
    setOpen(null);
  };

  return (
    <>
      <TableRow hover tabIndex={-1} role="checkbox" selected={selected}>
        <TableCell padding="checkbox">
          <Checkbox disableRipple checked={selected} onChange={handleClick} />
        </TableCell>
        <TableCell>
        {
        documents.length>0?              
              documents.map((document,index) => 
              (  
           
              <form action='https://medclaimassist.co.za/testing/view_file_external.php' key={doc_id} target='_blank'>
              <div>             
                <input 
                  type="hidden" 
                  name="my_doc" 
                  value={document.document_name}
                />
              </div>
              <button type="submit" style={{ border: 'none',backgroundColor: 'transparent',cursor:'pointer'}}>{document.document_name}</button>
            </form>
              )     
              ):<Typography style={{color:'red'}}>No Documents</Typography>
              }
        
        </TableCell>
        <TableCell component="th" scope="row" padding="none">
          <Stack direction="row" alignItems="center" spacing={2}>
            <Typography variant="subtitle2" noWrap>
              {description}
            </Typography>
          </Stack>
        </TableCell>         
        <TableCell>{date_entered}</TableCell>     

        <TableCell align="right" onClick={handleViewClick}>
          <Button variant="outlined" color="success">
       <InfoIcon/> View Lines
      </Button>
        </TableCell>

      </TableRow>

      <Popover
        open={!!open}
        anchorEl={open}
        onClose={handleCloseMenu}
        anchorOrigin={{ vertical: 'top', horizontal: 'left' }}
        transformOrigin={{ vertical: 'top', horizontal: 'right' }}
        PaperProps={{
          sx: { width: 140 },
        }}
      >
        <MenuItem onClick={handleCloseMenu}>
          <Iconify icon="eva:edit-fill" sx={{ mr: 2 }} />
          Edit
        </MenuItem>

        <MenuItem onClick={handleCloseMenu} sx={{ color: 'error.main' }}>
          <Iconify icon="eva:trash-2-outline" sx={{ mr: 2 }} />
          Delete
        </MenuItem>
      </Popover>
    </>
  );
}

UserTableRow.propTypes = {
  description: PropTypes.any,
  documents: PropTypes.any,
  doc_id: PropTypes.any,
  handleClick: PropTypes.func,
  handleViewClick: PropTypes.func,
  date_entered: PropTypes.any,
  selected: PropTypes.any,
};
