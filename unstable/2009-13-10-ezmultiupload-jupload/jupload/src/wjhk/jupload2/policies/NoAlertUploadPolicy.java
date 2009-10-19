package wjhk.jupload2.policies;

import java.io.File;

import wjhk.jupload2.context.JUploadContext;
import wjhk.jupload2.exception.JUploadException;
import wjhk.jupload2.exception.JUploadExceptionStopAddingFiles;
import wjhk.jupload2.filedata.FileData;

/**
 * @author etienne_sf
 *
 */
public class NoAlertUploadPolicy extends DefaultUploadPolicy {

    /**
     * @param juploadContext
     * @throws JUploadException
     */
    public NoAlertUploadPolicy(JUploadContext juploadContext) throws JUploadException {
        super(juploadContext);
    }

    /**
     * @see wjhk.jupload2.policies.DefaultUploadPolicy#createFileData(java.io.File, java.io.File)
     */
    public FileData createFileData(File file, File root)
            throws JUploadExceptionStopAddingFiles {
        if (!fileFilterAccept(file)) {
            return null;
        } else {
            return super.createFileData(file, root);
        }
    }

}
