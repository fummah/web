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

import Label from 'src/components/label';
import Iconify from 'src/components/iconify';

// ----------------------------------------------------------------------

export default function UserTableRow({
  selected,
  claim_number,
  medical_scheme,
  date_entered,
  charged_amnt,
  Open,
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

        <TableCell component="th" scope="row" padding="none">
          <Stack direction="row" alignItems="center" spacing={2}>
            <Typography variant="subtitle2" noWrap>
              {claim_number}
            </Typography>
          </Stack>
        </TableCell>

        <TableCell>{medical_scheme}</TableCell>

        <TableCell>{date_entered}</TableCell>        

        <TableCell align="center">{charged_amnt}</TableCell>

        <TableCell>
          <Label color={(Open === '1' && 'error') || 'success'}>{(Open === '1' && 'Open') || 'Closed'}</Label>
        </TableCell>
          <TableCell>
          <Button variant="outlined" onClick={handleViewClick}>
       <InfoIcon/> Tip
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
  claim_number: PropTypes.any,
  medical_scheme: PropTypes.any,
  handleClick: PropTypes.func,
  handleViewClick:PropTypes.func,
  date_entered: PropTypes.any,
  charged_amnt: PropTypes.any,
  Open: PropTypes.any,
  selected: PropTypes.any,
};
