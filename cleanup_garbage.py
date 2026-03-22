import os

files_to_delete = [
    "'required",
    "'required'",
    "count()",
    "delete()",
    "genieacs_id",
    "get()",
    "password",
    "ssid",
    "validate([",
    "check_devices.php",
    "cleanup_now.php",
    "run_sync.php"
]

for file in files_to_delete:
    try:
        if os.path.exists(file):
            os.remove(file)
            print(f"Deleted: {file}")
        else:
            print(f"Not found: {file}")
    except Exception as e:
        print(f"Error deleting {file}: {e}")
