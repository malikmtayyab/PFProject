import './App.css';
import {Routes,Route } from 'react-router-dom';
import Signup from './pages/Signup';
import Login from './pages/Login';
import WorkSpaceCreation from './pages/WorkSpaceCreation';
import Dashboard from './pages/Dashboard';

function App() {
  return (
  <>
    

  <Routes>
    <Route exact path='/' element={<Login/>} />
    <Route exact path='/signup' element={<Signup/>}/>
    <Route exact path='/signup/createworkspace' element={<WorkSpaceCreation/>}  />
    <Route exact path='/creator/dashboard' element={<Dashboard/> }/> 
  </Routes>
  

  </>
  );
}

export default App;
