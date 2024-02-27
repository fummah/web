import * as React from 'react';
import { useParams } from 'react-router-dom';
import { Divider, Descriptions } from 'antd';

import Box from '@mui/material/Box';
import Grid from '@mui/material/Grid';
import List from '@mui/material/List';
import Stack from '@mui/material/Stack';
import { styled } from '@mui/material/styles';
import ListItem from '@mui/material/ListItem';
import Typography from '@mui/material/Typography';
import FolderIcon from '@mui/icons-material/Folder';
import ListItemIcon from '@mui/material/ListItemIcon';
import ListItemText from '@mui/material/ListItemText';

import useAxiosFetch from 'src/hooks/use-axios';

import Error from 'src/components/response/error';
import ChatBox from 'src/components/response/chat';
import Loader from 'src/components/response/loader';
import ClaimLines from 'src/components/others/claim-lines';

const Demo = styled('div')(({ theme }) => ({
  backgroundColor: theme.palette.background.paper,
}));

export default function ClaimDetails()
{
  const { claim_id,type } = useParams();
  const [dense] = React.useState(false);
  const [documents, setDocuments] = React.useState([]);
  const [notes, setNotes] = React.useState([]);
  const [doctors, setDoctors] = React.useState([]);
  const [items, setItems] = React.useState([]);

  const { isLoading: isLoadingClaim, isError: isErrorClaim, data: dataClaim,statusCode:statusCodeClaims } = useAxiosFetch('getclaim','GET',{'claim_id':claim_id,'type':type}); 

  React.useEffect(() => {
   if(dataClaim && statusCodeClaims===200)
   {
    setDocuments(dataClaim.documents);   
    setNotes(dataClaim.notes);  
    setDoctors(dataClaim.doctors);
    setItems(myitems(dataClaim.claim));  
    console.log(dataClaim);
    }   
   // eslint-disable-next-line react-hooks/exhaustive-deps
   }, [dataClaim]);  

   const myitems = (claim) => {
    const i =  [
      { key: '1', label: 'Claim Number',  children: claim.claim_number, },
      { key: '2', label: 'Policy Number', children: claim.policy_number, },
      { key: '3', label: 'Date Entered', children: claim.date_entered, },
      { key: '4', label: 'Medical Scheme', children: claim.medical_scheme, },
      { key: '5', label: 'Scheme Option', children: claim.scheme_option, },
      { key: '6', label: 'Scheme Number', children: claim.scheme_number, },
      { key: '7', label: 'Assigned To', children: claim.username,},
      { key: '8', label: 'Total charged Amount', children: claim.charged_amnt,},
      { key: '9', label: 'Scheme Amount', children: claim.scheme_paid,},
      { key: '10', label: 'Member Portion', children: claim.gap,},
      { key: '11', label: 'Service Date', children: claim.Service_Date,},
      { key: '12', label: 'Status', children: claim.Open,},
    ];
    return i;
   };
  
  return (
    <>
  <Descriptions title="Claim Information" items={items}/>
  {isLoadingClaim?<Loader/>:null}
  {isErrorClaim?<Error mymessage={dataClaim.message}/>:null}
  <Stack spacing={3}>
  <Divider>Doctors({doctors.length})</Divider>
  {doctors.length>0?doctors.map((doctor,index) => 
  <><Typography key={index} align='center' style={{color:"#54bf99"}}>{doctor.full_name} ({doctor.practice_number})</Typography><ClaimLines claim_lines={doctor.claim_lines}/></>
  ):<Typography>No Doctors</Typography>}
<Box component="form">

      <Grid container spacing={2}>

             
              <Grid item xs={12} sm={8}>
              <Divider>Notes</Divider>
              <Demo>
              <List dense={dense} style={{marginLeft:'20px',marginRight:'20px'}}>
              {notes.length>0?              
              notes.map((note,index) => <ChatBox key={index} mymessage={note.intervention_desc} time={note.date_entered}/>
              ):<Typography>No Notes</Typography>}
              </List>
              </Demo>
              </Grid>
              <Grid item xs={12} sm={4}>
                <Divider>Documents</Divider>
              
          <Demo>
            <List dense={dense}>
            {documents.length>0?              
              documents.map((document,index) => 
                <ListItem key={index}>
                  <ListItemIcon>
                    <FolderIcon />
                  </ListItemIcon>
                  <ListItemText
                    primary={document.doc_description}
                    secondary="File"
                  />
                </ListItem>
              ):<Typography>No Documents</Typography>}
               
            </List>
          </Demo>
              </Grid>
              </Grid>
              </Box>
              </Stack>
    </>
  )
}

