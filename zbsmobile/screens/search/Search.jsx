import { View, Text, TextInput, TouchableOpacity, StatusBar, FlatList } from 'react-native'
import React,{useState,useEffect} from 'react'
import { SafeAreaView } from 'react-native-safe-area-context';
import reusable from '../../components/Reusable/reusable.style';
import styles from './search.style';
import { Feather } from '@expo/vector-icons';
import { COLORS } from '../../constants/theme';
import AppBar from '../../components/Reusable/AppBar'
import { HeightSpacer } from '../../components';
import SearchTile from '../../components/Reusable/SearchTile';
import useAxiosFetch from '../../hooks/use-axios';

const Search = () => {
  const [searchKey,setSearchKey] = useState('');
  const [searchUp, setSearchUp] = useState(false);
  const [search, setSearch] = useState([]);
  const [postData, setPostData] = useState({});
  
  const { isLoading: isLoadingSearch, isError: isErrorSearch, data: dataSearch,statusCode: statusCodeSearch } = useAxiosFetch('search','POST',postData,searchUp);


  useEffect(() => {
  
    if(dataSearch && statusCodeSearch===200)
    {   
      setSearchUp(false);  
      setSearch(dataSearch.searched_members);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [dataSearch]);
    
  const handleSearch = (text) => {
    setSearchKey(text);
    if(text.length>2)
    {
    setPostData({"search_term":text}); 
    setSearchUp(true);
    }
    else{
      setSearch([]);
    }
  
};
  
  return (
    <>
    <StatusBar barStyle="light-content" backgroundColor="#449282" />
   <SafeAreaView style={reusable.container}>
    <View style={{height:50}}>
    <AppBar title={'Search'} color={COLORS.white}  icon={'home'} color1={COLORS.white}/>
    </View>
<View style={styles.searcContainer}>
  <View style={styles.searchWrapper}>
    <TextInput
style={styles.input}
value={searchKey}
onChangeText={handleSearch}
placeholder='Search Member...'
    />
  </View>

  <TouchableOpacity style={styles.searchBtn}>
<Feather 
name='search'
size={24}
color={COLORS.white}
/>
  </TouchableOpacity>
</View>
{isLoadingSearch && <Text style={{color:COLORS.red,marginBottom:10,textAlign:'center'}}>Searching ...</Text>}
{isErrorSearch && <Text style={{color:COLORS.red,marginBottom:10,textAlign:'center'}}>{dataSearch.message}</Text>}
{search.length < 1 ? (
  <View>
    <HeightSpacer height={'20%'}/>

  </View>
 
):(
 <FlatList
data={search}
keyExtractor={(item) => item.member_id}
showsVerticalScrollIndicator={false}
renderItem={({item}) => (
  <View style={styles.tile}>
  <SearchTile item={item} />
  </View>
)}
 />
)}
<HeightSpacer height={'10%'}/>
   </SafeAreaView>
   </>
  )
}

export default Search