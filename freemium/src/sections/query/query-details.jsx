import * as React from 'react';
import { useParams } from 'react-router-dom';
import { Divider, Descriptions } from 'antd';

import Box from '@mui/material/Box';
import Grid from '@mui/material/Grid';
import List from '@mui/material/List';
import Stack from '@mui/material/Stack';
import ListItem from '@mui/material/ListItem';
import { styled } from '@mui/material/styles';
import Typography from '@mui/material/Typography';
import FolderIcon from '@mui/icons-material/Folder';
import ListItemIcon from '@mui/material/ListItemIcon';

import useAxiosFetch from 'src/hooks/use-axios';

import ChatBox from 'src/components/response/chat';
import Loader from 'src/components/response/loader';
import ClaimLines from 'src/components/others/claim-lines';

const Demo = styled('div')(({ theme }) => ({
  backgroundColor: theme.palette.background.paper,
}));

export default function QueryDetails()
{
  const { query_id } = useParams();
  const [dense, setDense] = React.useState(false);
  const [doctors, setDoctors] = React.useState([]);
  const [documents, setDocuments] = React.useState([]);
  const [notes, setNotes] = React.useState([]);
  const [items, setItems] = React.useState([]);
  
  const { isLoading: isLoadingQuery, isError: isErrorQuery, data: dataQuery } = useAxiosFetch('getquery','GET',{'query_id':query_id}); 

  React.useEffect(() => {
   if(dataQuery)
   {
    console.log(dataQuery);
    setDocuments(dataQuery.documents);   
    setNotes(dataQuery.notes);  
    setItems(myitems(dataQuery.query,dataQuery.claim));
    setDoctors(dataQuery.doctors?.original?.doctors); 
    setDense(false);
    }   
   // eslint-disable-next-line react-hooks/exhaustive-deps
   }, [dataQuery]);  

   const myitems = (query,claim) => {
    const i =  [
      { key: '2', label: 'Ref.No', children: claim?.claim_number, },
      { key: '1', label: 'Category',  children: query.category, },      
      { key: '3', label: 'Status', children: claim?.Open?"Open":"Closed", },
      { key: '4', label: 'Date Entered', children: query.date_entered, },
      { key: '5', label: 'Assigned To', children: claim?.username,},
      { key: '6', label: 'Description', children: query.description,},
    ];
    return i;
   };
  
  return (
    <>
 
  <Descriptions title="Query Information" items={items}/>
  {isLoadingQuery?<Loader/>:null}
        {isErrorQuery?<Typography variant="h6">There is an error</Typography>:null}
  <Stack spacing={3}>
  <Divider>Doctors({doctors.length})</Divider>
  {doctors.length>0?doctors.map((doctor,index) => 
  <><Typography key={index} align='center' style={{color:"#54bf99"}}>{doctor.full_name} ({doctor.practice_number})</Typography><ClaimLines claim_lines={doctor.claim_lines}/></>
  ):<Typography align='center' style={{color:"red"}}>No Doctors</Typography>}
<Box component="form">

      <Grid container spacing={2}>
             
              <Grid item xs={12} sm={8}>
              <Divider>Notes</Divider>
              <Demo>
              <List dense={dense} style={{marginLeft:'20px',marginRight:'20px'}}>
              {notes.length>0?              
              notes.map((note,index) => <ChatBox key={index} mymessage={note.description} time={note.date_entered}/>
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
                
                    <form action='https://medclaimassist.co.za/testing/view_file_external.php' key={index} target='_blank'>
              <div>             
                <input 
                  type="hidden" 
                  name="my_doc" 
                  value={document.document_name}
                />
              </div>
              <button type="submit" style={{ border: 'none',backgroundColor: 'transparent',cursor:'pointer'}}>{document.document_name}</button>
            </form>
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

