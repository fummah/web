import { View, Text, TextInput, TouchableOpacity, Image, FlatList } from 'react-native'
import React,{useState,useEffect} from 'react'
import { SafeAreaView } from 'react-native-safe-area-context';
import reusable from '../../components/Reusable/reusable.style';
import styles from './search.style';
import { Feather } from '@expo/vector-icons';
import { COLORS } from '../../constants/theme';
import AppBar from '../../components/Reusable/AppBar'
import { HeightSpacer } from '../../components';
import SearchTile from '../../components/Reusable/SearchTile';

const Search = () => {
  const [searchKey,setSearchKey] = useState('');
  const [searchResults,setSearchResults] = useState([]);
  const search = [
    {
        "member_id": "1",
        "member_name": "Gibson Sahwenje",
        "location":"Fishoek (A)",
        "contact_number":"+23 675 9870"
    },
    {
      "member_id": "2",
      "member_name": "Silverster Mairosi",
      "location":"Danoon (B)",
      "contact_number":"+23 675 9870"
    },
    {
      "member_id": "3",
      "member_name": "Rumbo Sahwenje",
      "location":"Muzeinbery (B)",
      "contact_number":"+23 675 9870"
    },
    {
      "member_id": "4",
      "member_name": "Gibson Sahwenje",
      "location":"Picketburg (A)",
      "contact_number":"+23 675 9870"
    },
    {
      "member_id": "5",
      "member_name": "Gibson Sahwenje",
      "location":"Fishoek (A)",
      "contact_number":"+23 675 9870"
    },
    {
      "member_id": "6",
      "member_name": "Gibson Sahwenje",
      "location":"Nyanga (B)",
      "contact_number":"+23 675 9870"
    }
];
  return (
   <SafeAreaView style={reusable.container}>
    <View style={{height:50}}>
    <AppBar title={'Search'} color={COLORS.white}  icon={'home'} color1={COLORS.white}/>
    </View>
<View style={styles.searcContainer}>
  <View style={styles.searchWrapper}>
    <TextInput
style={styles.input}
value={searchKey}
onChangeText={setSearchKey}
placeholder='Search Member'
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

{search.length === 0 ? (
  <View>
    <HeightSpacer height={'20%'}/>
     <Image
source={require('../../assets/images/zbssearch.png')}
style={styles.searchImage}
  />
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
   </SafeAreaView>
  )
}

export default Search