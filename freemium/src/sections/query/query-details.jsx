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
import ListItemText from '@mui/material/ListItemText';

import useAxiosFetch from 'src/hooks/use-axios';

import ChatBox from 'src/components/response/chat';
import Loader from 'src/components/response/loader';

const Demo = styled('div')(({ theme }) => ({
  backgroundColor: theme.palette.background.paper,
}));

export default function QueryDetails()
{
  const { query_id } = useParams();
  const [dense, setDense] = React.useState(false);
  const [documents, setDocuments] = React.useState([]);
  const [notes, setNotes] = React.useState([]);
  const [items, setItems] = React.useState([]);

  const { isLoading: isLoadingQuery, isError: isErrorQuery, data: dataQuery } = useAxiosFetch('getquery','GET',{'query_id':query_id}); 

  React.useEffect(() => {
   if(dataQuery)
   {
    setDocuments(dataQuery.documents);   
    setNotes(dataQuery.notes);  
    setItems(myitems(dataQuery.query));  
    setDense(false);
    }   
   // eslint-disable-next-line react-hooks/exhaustive-deps
   }, [dataQuery]);  

   const myitems = (query) => {
    const i =  [
      { key: '1', label: 'Category',  children: query.category, },
      { key: '2', label: 'Source', children: query.source, },
      { key: '3', label: 'Status', children: query.status, },
      { key: '4', label: 'Date Entered', children: query.date_entered, },
      { key: '5', label: 'Assigned To', children: query.assigned_to,},
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
                  <ListItemText
                    primary={document.document_name}
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

