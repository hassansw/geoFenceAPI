

{
  private File file;
  private static final String FILENAME = "jsondata.json";

}

public void onCreate(){

  File extFileDir = getExternalFilesDir(null);
  String path = extFileDir.getAbsolutePath();

  //Now readFile
        File extFileDir = getExternalFilesDir(null);
        String path = extFileDir.getAbsolutePath();
        file = new File(extFileDir,FILENAME);

        JSONArray jsonArray = null;

        try {
            jsonArray = readFile();
        } catch (IOException | JSONException e) {
            e.printStackTrace();
        }




        String mName = null;
        String mContact = null;
        String mMail = null;


        try {
            mName = jsonArray.getJSONObject(0).getString("name");
            mMail = jsonArray.getJSONObject(0).getString("email");
            mContact = jsonArray.getJSONObject(0).getString("contact");

        } catch (JSONException e) {
            e.printStackTrace();
        }

}


    public void createFile(String name, String email, String number) throws IOException, JSONException {

        if (!checkedExternalStorageState()){
            return;
        }

        JSONArray data = new JSONArray();
        JSONObject user;
        

        user = new JSONObject();
        user.put("name",name);
        user.put("email",email);
        user.put("contact",number);
        data.put(user);

        String text = data.toString();

        //FileOutputStream fos = openFileOutput("userdata.json", MODE_PRIVATE);
        FileOutputStream fos = new FileOutputStream(file);
        fos.write(text.getBytes());
        fos.close();

        Toast.makeText(this, "Success", Toast.LENGTH_SHORT).show();
    }

    public boolean checkedExternalStorageState(){
        String state = Environment.getExternalStorageState();

        if ( state.equals(Environment.MEDIA_MOUNTED)){
            return true;
        } else if (state.equals(Environment.MEDIA_MOUNTED_READ_ONLY)){
            return false;
        } else {
            Toast.makeText(this, "Error", Toast.LENGTH_SHORT).show();
        }
        return false;
    }

    protected boolean isOnline() {

            ConnectivityManager cm = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo netInfo = cm.getActiveNetworkInfo();
            if (netInfo != null && netInfo.isConnectedOrConnecting()) {
                return true;
            } else {
                return false;
            }
        }

    public JSONArray readFile() throws IOException, JSONException , NullPointerException {

            //FileInputStream fis = openFileInput("userdata.json");
            FileInputStream fis = new FileInputStream(file);
            BufferedInputStream bis = new BufferedInputStream(fis);
            StringBuffer b = new StringBuffer();
            while (bis.available() != 0) {
                char c = (char) bis.read();
                b.append(c);
            }

            bis.close();
            fis.close();

            JSONArray data = new JSONArray(b.toString());
            return data;
        }
