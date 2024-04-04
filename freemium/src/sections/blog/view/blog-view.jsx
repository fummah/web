import { useState,useEffect } from 'react';

import Stack from '@mui/material/Stack';
import Container from '@mui/material/Container';
import Grid from '@mui/material/Unstable_Grid2';
import Typography from '@mui/material/Typography';

import useAxiosFetch from 'src/hooks/use-axios';

import Error from 'src/components/response/error';
import Loader from 'src/components/response/loader';

import PostCard from '../post-card';
import PostSort from '../post-sort';
import PostSearch from '../post-search';

// ----------------------------------------------------------------------

export default function BlogView() {

  const [posts,setPosts] = useState([]);
  const { isLoading,isError, data,statusCode } = useAxiosFetch('getblogs','GET', {});
 
  useEffect(() => {
  
   if(data && statusCode===200)
   {    
    setPosts(data.blogs);
     
   }   
   // eslint-disable-next-line react-hooks/exhaustive-deps
   }, [data]);
  return (
    <Container>
            {isLoading?<Loader/>:null}
      {isError?<Error mymessage={data.message}/>:null}
      <Stack direction="row" alignItems="center" justifyContent="space-between" mb={5}>
        <Typography variant="h4">Blog Posts</Typography>

      </Stack>

      <Stack mb={5} direction="row" alignItems="center" justifyContent="space-between">
        <PostSearch posts={posts} />
        <PostSort
          options={[
            { value: 'latest', label: 'Latest' },
            { value: 'popular', label: 'Popular' },
            { value: 'oldest', label: 'Oldest' },
          ]}
        />
      </Stack>

      <Grid container spacing={3}>
        {posts.length>0?posts.map((post, index) => (
          <PostCard key={post.id} post={post} index={index} />
        )):<Typography align='center' style={{color:"red",width:"100%"}}>No Blog Posts</Typography>}
      </Grid>
    </Container>
  );
}
