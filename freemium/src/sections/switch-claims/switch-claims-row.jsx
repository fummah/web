import { useState } from 'react';
import PropTypes from 'prop-types';

import Button from '@mui/material/Button';
import Popover from '@mui/material/Popover';
import TableRow from '@mui/material/TableRow';
import Checkbox from '@mui/material/Checkbox';
import MenuItem from '@mui/material/MenuItem';
import TableCell from '@mui/material/TableCell';
import InfoIcon from '@mui/icons-material/Info';
import ChecklistRtl from '@mui/icons-material/ChecklistRtl';

import QueryDrawer from 'src/drawers/add_query';

import Iconify from 'src/components/iconify';

// ----------------------------------------------------------------------

export default function UserTableRow({
  selected,
  doctors,
  claim_number,
  service_date,
  charged_amnt,
  scheme_paid,
  gap,
  plan,
  handleClick,
  handleViewClick,
  handleTipClick,
}) {
  const [open, setOpen] = useState(null);


  const handleCloseMenu = () => {
    setOpen(null);
  };
  console.log("My Plan");
  console.log(plan);

  return (
    <>
      <TableRow hover tabIndex={-1} role="checkbox" selected={selected}>
        <TableCell padding="checkbox">
          <Checkbox disableRipple checked={selected} onChange={handleClick} />
        </TableCell>

       <TableCell>{doctors}</TableCell>
        <TableCell>{service_date}</TableCell>
        <TableCell>{charged_amnt}</TableCell>
        <TableCell align="center">{scheme_paid}</TableCell>
        <TableCell>{gap}</TableCell>
        <TableCell>
        <Button variant="outlined" onClick={handleTipClick}>
       <InfoIcon/> Tip
      </Button>
      </TableCell>
      
        <TableCell>
        {plan === null?
       <QueryDrawer myvariant="outlined" mycolor="success" mytext="Help" plan={plan}/>   
:
<>
<QueryDrawer myvariant="outlined" mycolor="success" mytext="Help" plan={plan}/>  
<Button style={{marginTop:'10px'}} variant="outlined" color="error" onClick={handleViewClick}>
       <ChecklistRtl/> View
      </Button>
      </>
        }   
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
  doctors: PropTypes.any,
  claim_number: PropTypes.any,
  handleClick: PropTypes.func,
  handleViewClick: PropTypes.func,
  handleTipClick: PropTypes.func,
  charged_amnt: PropTypes.any,
  service_date: PropTypes.any,
  selected: PropTypes.any,
  scheme_paid: PropTypes.any,
  gap: PropTypes.any,
  plan: PropTypes.any,
};
